<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $shipment_id
 * @property string $shippable_type
 * @property int $shippable_id
 * @property string|null $description
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Shipment $shipment
 * @property-read Model|\Eloquent $shippable
 *
 * @method static Builder<static>|ShipmentItem newModelQuery()
 * @method static Builder<static>|ShipmentItem newQuery()
 * @method static Builder<static>|ShipmentItem query()
 * @method static Builder<static>|ShipmentItem whereCreatedAt($value)
 * @method static Builder<static>|ShipmentItem whereDescription($value)
 * @method static Builder<static>|ShipmentItem whereId($value)
 * @method static Builder<static>|ShipmentItem whereQuantity($value)
 * @method static Builder<static>|ShipmentItem whereShipmentId($value)
 * @method static Builder<static>|ShipmentItem whereShippableId($value)
 * @method static Builder<static>|ShipmentItem whereShippableType($value)
 * @method static Builder<static>|ShipmentItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ShipmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'shippable_type',
        'shippable_id',
        'description',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    // Relationships

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function shippable(): MorphTo
    {
        return $this->morphTo();
    }
}
