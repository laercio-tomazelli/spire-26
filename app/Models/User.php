<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'partner_id',
        'manufacturer_id',
        'customer_id',
        'user_type',
        'is_partner_admin',
        'created_by_user_id',
        'username',
        'name',
        'email',
        'password',
        'phone',
        'mobile',
        'avatar',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_partner_admin' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function createdUsers(): HasMany
    {
        return $this->hasMany(User::class, 'created_by_user_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'user_brands')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'user_partners')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function serviceOrdersAsTechnician(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'technician_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(UserActivityLog::class);
    }

    // Permission Helpers

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Check direct permission (can be granted or revoked)
        $directPermission = $this->permissions()->where('slug', $permissionSlug)->first();

        if ($directPermission) {
            return $directPermission->pivot->granted;
        }

        // Check role permissions
        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('slug', $permissionSlug))
            ->exists();
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        foreach ($permissionSlugs as $slug) {
            if ($this->hasPermission($slug)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        foreach ($permissionSlugs as $slug) {
            if (! $this->hasPermission($slug)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * Get the default brand for the user.
     */
    public function getDefaultBrand(): ?Brand
    {
        return $this->brands()->wherePivot('is_default', true)->first()
            ?? $this->brands()->first();
    }

    /**
     * Get the primary partner for the user (for technicians).
     */
    public function getPrimaryPartner(): ?Partner
    {
        return $this->partners()->wherePivot('is_primary', true)->first()
            ?? $this->partners()->first();
    }

    // User Type Helpers

    /**
     * Check if user is a Spire admin/operator.
     */
    public function isSpire(): bool
    {
        return $this->user_type === 'spire';
    }

    /**
     * Check if user is a Partner (Posto Autorizado) user.
     */
    public function isPartner(): bool
    {
        return $this->user_type === 'partner';
    }

    /**
     * Check if user is the admin of their Partner.
     */
    public function isPartnerAdmin(): bool
    {
        return $this->user_type === 'partner' && $this->is_partner_admin;
    }

    /**
     * Check if user is a Manufacturer user.
     */
    public function isManufacturer(): bool
    {
        return $this->user_type === 'manufacturer';
    }

    /**
     * Check if user is a Client (end customer).
     */
    public function isClient(): bool
    {
        return $this->user_type === 'client';
    }

    /**
     * Get the entity (Partner, Manufacturer, Customer) this user belongs to.
     */
    public function getLinkedEntity(): Partner|Manufacturer|Customer|null
    {
        return match ($this->user_type) {
            'partner' => $this->partner,
            'manufacturer' => $this->manufacturer,
            'client' => $this->customer,
            default => null,
        };
    }

    /**
     * Check if this user can create other users (Partner admins can create partner users).
     */
    public function canCreateUsers(): bool
    {
        // Spire users with permission can always create
        if ($this->isSpire() && $this->hasPermission('users.create')) {
            return true;
        }

        // Partner admins can create users for their partner
        return $this->isPartnerAdmin();
    }

    /**
     * Get users created by this user (for partner hierarchy).
     */
    public function getSubordinateUsers()
    {
        if (! $this->isPartnerAdmin()) {
            return collect();
        }

        return User::where('partner_id', $this->partner_id)
            ->where('id', '!=', $this->id)
            ->get();
    }
}
