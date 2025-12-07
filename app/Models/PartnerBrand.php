<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
