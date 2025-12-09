<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $tenant_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|Team forTenant(int $tenantId)
 * @method static Builder<static>|Team newModelQuery()
 * @method static Builder<static>|Team newQuery()
 * @method static Builder<static>|Team query()
 * @method static Builder<static>|Team whereCreatedAt($value)
 * @method static Builder<static>|Team whereDescription($value)
 * @method static Builder<static>|Team whereId($value)
 * @method static Builder<static>|Team whereIsActive($value)
 * @method static Builder<static>|Team whereName($value)
 * @method static Builder<static>|Team whereSlug($value)
 * @method static Builder<static>|Team whereTenantId($value)
 * @method static Builder<static>|Team whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Team extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Team $team): void {
            if (empty($team->slug)) {
                $team->slug = Str::slug($team->name);
            }
        });
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Users that belong to this team.
     *
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->withPivot('is_leader')
            ->withTimestamps();
    }

    /**
     * Team leaders.
     *
     * @return BelongsToMany<User, $this>
     */
    public function leaders(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->wherePivot('is_leader', true)
            ->withTimestamps();
    }

    /**
     * Roles assigned to this team (all members inherit these).
     *
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'team_roles')
            ->withTimestamps();
    }

    /**
     * Direct permissions assigned to this team.
     *
     * @return BelongsToMany<Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'team_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    /**
     * Get all permissions from this team (from roles + direct permissions).
     *
     * @return Collection<int, Permission>
     */
    public function getAllPermissions(): Collection
    {
        // Get permissions from roles
        $rolePermissions = Permission::query()
            ->whereHas('roles', function (Builder $query): void {
                $query->whereIn('roles.id', $this->roles()->pluck('roles.id'));
            })
            ->get();

        // Get direct team permissions (granted only)
        $directPermissions = $this->permissions()
            ->wherePivot('granted', true)
            ->get();

        return $rolePermissions->merge($directPermissions)->unique('id');
    }

    /**
     * Check if team has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Check direct permissions first
        $directPermission = $this->permissions()
            ->where('slug', $permissionSlug)
            ->first();

        if ($directPermission !== null) {
            return (bool) $directPermission->pivot->granted;
        }

        // Check role permissions
        return $this->roles()
            ->whereHas('permissions', function (Builder $query) use ($permissionSlug): void {
                $query->where('slug', $permissionSlug);
            })
            ->exists();
    }

    /**
     * Add a user to the team.
     */
    public function addUser(User $user, bool $isLeader = false): void
    {
        $this->users()->syncWithoutDetaching([
            $user->id => ['is_leader' => $isLeader],
        ]);
    }

    /**
     * Remove a user from the team.
     */
    public function removeUser(User $user): void
    {
        $this->users()->detach($user->id);
    }

    /**
     * Assign a role to the team.
     */
    public function assignRole(Role $role): void
    {
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove a role from the team.
     */
    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role->id);
    }

    /**
     * Give a permission to the team.
     */
    public function givePermission(Permission $permission): void
    {
        $this->permissions()->syncWithoutDetaching([
            $permission->id => ['granted' => true],
        ]);
    }

    /**
     * Revoke a permission from the team.
     */
    public function revokePermission(Permission $permission): void
    {
        $this->permissions()->syncWithoutDetaching([
            $permission->id => ['granted' => false],
        ]);
    }
}
