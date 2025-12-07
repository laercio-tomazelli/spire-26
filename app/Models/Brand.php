<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'manufacturer_id',
        'name',
        'slug',
        'code',
        'logo_url',
        'settings',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function productLines(): HasMany
    {
        return $this->hasMany(ProductLine::class);
    }

    public function productModels(): HasMany
    {
        return $this->hasMany(ProductModel::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(PartnerBrand::class);
    }

    public function monthlyClosings(): HasMany
    {
        return $this->hasMany(MonthlyClosing::class);
    }
}
