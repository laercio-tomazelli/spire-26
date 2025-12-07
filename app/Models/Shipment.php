<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'carrier_id',
        'tracking_code',
        'type',
        'origin_name',
        'origin_document',
        'origin_address',
        'origin_address_number',
        'origin_neighborhood',
        'origin_city',
        'origin_state',
        'origin_postal_code',
        'destination_name',
        'destination_document',
        'destination_address',
        'destination_address_number',
        'destination_neighborhood',
        'destination_city',
        'destination_state',
        'destination_postal_code',
        'weight',
        'length',
        'width',
        'height',
        'declared_value',
        'volumes',
        'shipping_cost',
        'insurance_cost',
        'total_cost',
        'status',
        'shipped_at',
        'estimated_delivery_at',
        'delivered_at',
        'invoice_number',
        'invoice_key',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:3',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'declared_value' => 'decimal:2',
            'volumes' => 'integer',
            'shipping_cost' => 'decimal:2',
            'insurance_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'shipped_at' => 'datetime',
            'estimated_delivery_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(ShipmentTrackingEvent::class);
    }

    // Helpers

    public function isDelivered(): bool
    {
        return ! is_null($this->delivered_at);
    }

    public function isOutbound(): bool
    {
        return $this->type === 'outbound';
    }

    public function isInbound(): bool
    {
        return $this->type === 'inbound';
    }

    /**
     * Get the tracking URL.
     */
    protected function getTrackingUrlAttribute(): ?string
    {
        if (! $this->carrier || ! $this->tracking_code) {
            return null;
        }

        // URL de rastreio padrão (pode ser sobrescrito por cada carrier)
        return match ($this->carrier->code ?? '') {
            'correios' => 'https://rastreamento.correios.com.br/app/index.php?objetos='.$this->tracking_code,
            'jadlog' => 'https://www.jadlog.com.br/jadlog/tracking?cte='.$this->tracking_code,
            default => null,
        };
    }
}
