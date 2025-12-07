<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrier extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'document',
        'phone',
        'email',
        'website',
        'tracking_url_template',
        'api_endpoint',
        'api_credentials',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'api_credentials' => 'encrypted:array',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Get the tracking URL for a given tracking code.
     */
    public function getTrackingUrl(string $trackingCode): ?string
    {
        if (! $this->tracking_url_template) {
            return null;
        }

        return str_replace('{tracking_code}', $trackingCode, $this->tracking_url_template);
    }
}
