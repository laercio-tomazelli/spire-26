<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
