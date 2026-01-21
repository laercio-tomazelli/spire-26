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
 * @property int $invoice_id
 * @property int|null $order_id
 * @property int|null $service_order_id
 * @property bool $is_bound
 * @property string|null $event
 * @property string|null $status
 * @property string|null $colors
 * @property string|null $icon
 * @property string|null $part_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Invoice $invoice
 * @property-read Order|null $order
 * @property-read ServiceOrder|null $serviceOrder
 *
 * @method static Builder<static>|InvoiceComment newModelQuery()
 * @method static Builder<static>|InvoiceComment newQuery()
 * @method static Builder<static>|InvoiceComment query()
 * @method static Builder<static>|InvoiceComment whereColors($value)
 * @method static Builder<static>|InvoiceComment whereCreatedAt($value)
 * @method static Builder<static>|InvoiceComment whereEvent($value)
 * @method static Builder<static>|InvoiceComment whereIcon($value)
 * @method static Builder<static>|InvoiceComment whereId($value)
 * @method static Builder<static>|InvoiceComment whereInvoiceId($value)
 * @method static Builder<static>|InvoiceComment whereIsBound($value)
 * @method static Builder<static>|InvoiceComment whereOrderId($value)
 * @method static Builder<static>|InvoiceComment wherePartCode($value)
 * @method static Builder<static>|InvoiceComment whereServiceOrderId($value)
 * @method static Builder<static>|InvoiceComment whereStatus($value)
 * @method static Builder<static>|InvoiceComment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
