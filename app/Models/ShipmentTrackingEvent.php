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
 * @property int $shipment_id
 * @property Carbon $occurred_at
 * @property string $status
 * @property string|null $location
 * @property string|null $description
 * @property array<array-key, mixed>|null $raw_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Shipment $shipment
 *
 * @method static Builder<static>|ShipmentTrackingEvent newModelQuery()
 * @method static Builder<static>|ShipmentTrackingEvent newQuery()
 * @method static Builder<static>|ShipmentTrackingEvent query()
 * @method static Builder<static>|ShipmentTrackingEvent whereCreatedAt($value)
 * @method static Builder<static>|ShipmentTrackingEvent whereDescription($value)
 * @method static Builder<static>|ShipmentTrackingEvent whereId($value)
 * @method static Builder<static>|ShipmentTrackingEvent whereLocation($value)
 * @method static Builder<static>|ShipmentTrackingEvent whereOccurredAt($value)
 * @method static Builder<static>|ShipmentTrackingEvent whereRawData($value)
 * @method static Builder<static>|ShipmentTrackingEvent whereShipmentId($value)
 * @method static Builder<static>|ShipmentTrackingEvent whereStatus($value)
 * @method static Builder<static>|ShipmentTrackingEvent whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ShipmentTrackingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'occurred_at',
        'status',
        'location',
        'description',
        'raw_data',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'raw_data' => 'array',
        ];
    }

    // Relationships

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
