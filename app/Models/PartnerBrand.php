<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $partner_id
 * @property int $brand_id
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Brand $brand
 * @property-read Partner $partner
 *
 * @method static Builder<static>|PartnerBrand newModelQuery()
 * @method static Builder<static>|PartnerBrand newQuery()
 * @method static Builder<static>|PartnerBrand query()
 * @method static Builder<static>|PartnerBrand whereBrandId($value)
 * @method static Builder<static>|PartnerBrand whereCreatedAt($value)
 * @method static Builder<static>|PartnerBrand whereId($value)
 * @method static Builder<static>|PartnerBrand whereIsActive($value)
 * @method static Builder<static>|PartnerBrand wherePartnerId($value)
 * @method static Builder<static>|PartnerBrand whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PartnerBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'brand_id',
        'partner_code',
        'certification_number',
        'certification_expires_at',
        'service_types',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'certification_expires_at' => 'date',
            'service_types' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Check if certification is expired.
     */
    public function isCertificationExpired(): bool
    {
        if (! $this->certification_expires_at) {
            return false;
        }

        return $this->certification_expires_at->isPast();
    }
}
