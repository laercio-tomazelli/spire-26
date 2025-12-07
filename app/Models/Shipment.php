<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int|null $carrier_id
 * @property string|null $tracking_code
 * @property string $type
 * @property string|null $origin_name
 * @property string|null $origin_document
 * @property string|null $origin_address
 * @property string|null $origin_address_number
 * @property string|null $origin_neighborhood
 * @property string|null $origin_city
 * @property string|null $origin_state
 * @property string|null $origin_postal_code
 * @property string|null $destination_name
 * @property string|null $destination_document
 * @property string|null $destination_address
 * @property string|null $destination_address_number
 * @property string|null $destination_neighborhood
 * @property string|null $destination_city
 * @property string|null $destination_state
 * @property string|null $destination_postal_code
 * @property numeric|null $weight
 * @property numeric|null $length
 * @property numeric|null $width
 * @property numeric|null $height
 * @property numeric|null $declared_value
 * @property int $volumes
 * @property numeric|null $shipping_cost
 * @property numeric|null $insurance_cost
 * @property numeric|null $total_cost
 * @property string $status
 * @property Carbon|null $shipped_at
 * @property Carbon|null $estimated_delivery_at
 * @property Carbon|null $delivered_at
 * @property string|null $invoice_number
 * @property string|null $invoice_key
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Carrier|null $carrier
 * @property-read string|null $tracking_url
 * @property-read Collection<int, ShipmentItem> $items
 * @property-read int|null $items_count
 * @property-read Tenant $tenant
 * @property-read Collection<int, ShipmentTrackingEvent> $trackingEvents
 * @property-read int|null $tracking_events_count
 *
 * @method static Builder<static>|Shipment forTenant(int $tenantId)
 * @method static Builder<static>|Shipment newModelQuery()
 * @method static Builder<static>|Shipment newQuery()
 * @method static Builder<static>|Shipment query()
 * @method static Builder<static>|Shipment whereCarrierId($value)
 * @method static Builder<static>|Shipment whereCreatedAt($value)
 * @method static Builder<static>|Shipment whereDeclaredValue($value)
 * @method static Builder<static>|Shipment whereDeliveredAt($value)
 * @method static Builder<static>|Shipment whereDestinationAddress($value)
 * @method static Builder<static>|Shipment whereDestinationAddressNumber($value)
 * @method static Builder<static>|Shipment whereDestinationCity($value)
 * @method static Builder<static>|Shipment whereDestinationDocument($value)
 * @method static Builder<static>|Shipment whereDestinationName($value)
 * @method static Builder<static>|Shipment whereDestinationNeighborhood($value)
 * @method static Builder<static>|Shipment whereDestinationPostalCode($value)
 * @method static Builder<static>|Shipment whereDestinationState($value)
 * @method static Builder<static>|Shipment whereEstimatedDeliveryAt($value)
 * @method static Builder<static>|Shipment whereHeight($value)
 * @method static Builder<static>|Shipment whereId($value)
 * @method static Builder<static>|Shipment whereInsuranceCost($value)
 * @method static Builder<static>|Shipment whereInvoiceKey($value)
 * @method static Builder<static>|Shipment whereInvoiceNumber($value)
 * @method static Builder<static>|Shipment whereLength($value)
 * @method static Builder<static>|Shipment whereNotes($value)
 * @method static Builder<static>|Shipment whereOriginAddress($value)
 * @method static Builder<static>|Shipment whereOriginAddressNumber($value)
 * @method static Builder<static>|Shipment whereOriginCity($value)
 * @method static Builder<static>|Shipment whereOriginDocument($value)
 * @method static Builder<static>|Shipment whereOriginName($value)
 * @method static Builder<static>|Shipment whereOriginNeighborhood($value)
 * @method static Builder<static>|Shipment whereOriginPostalCode($value)
 * @method static Builder<static>|Shipment whereOriginState($value)
 * @method static Builder<static>|Shipment whereShippedAt($value)
 * @method static Builder<static>|Shipment whereShippingCost($value)
 * @method static Builder<static>|Shipment whereStatus($value)
 * @method static Builder<static>|Shipment whereTenantId($value)
 * @method static Builder<static>|Shipment whereTotalCost($value)
 * @method static Builder<static>|Shipment whereTrackingCode($value)
 * @method static Builder<static>|Shipment whereType($value)
 * @method static Builder<static>|Shipment whereUpdatedAt($value)
 * @method static Builder<static>|Shipment whereVolumes($value)
 * @method static Builder<static>|Shipment whereWeight($value)
 * @method static Builder<static>|Shipment whereWidth($value)
 *
 * @mixin \Eloquent
 */
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
