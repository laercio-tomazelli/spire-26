<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'order_id',
        'service_order_id',
        'is_bound',
        'event',
        'status',
        'colors',
        'icon',
        'part_code',
    ];

    protected function casts(): array
    {
        return [
            'is_bound' => 'boolean',
        ];
    }

    // Relationships

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}
