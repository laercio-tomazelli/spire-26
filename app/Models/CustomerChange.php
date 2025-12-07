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
 * @property int $customer_id
 * @property int $user_id
 * @property string $field_name
 * @property string|null $old_value
 * @property string|null $new_value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read ServiceOrder|null $serviceOrder
 * @property-read User|null $user
 *
 * @method static Builder<static>|CustomerChange newModelQuery()
 * @method static Builder<static>|CustomerChange newQuery()
 * @method static Builder<static>|CustomerChange query()
 * @method static Builder<static>|CustomerChange whereCreatedAt($value)
 * @method static Builder<static>|CustomerChange whereCustomerId($value)
 * @method static Builder<static>|CustomerChange whereFieldName($value)
 * @method static Builder<static>|CustomerChange whereId($value)
 * @method static Builder<static>|CustomerChange whereNewValue($value)
 * @method static Builder<static>|CustomerChange whereOldValue($value)
 * @method static Builder<static>|CustomerChange whereUpdatedAt($value)
 * @method static Builder<static>|CustomerChange whereUserId($value)
 *
 * @mixin \Eloquent
 */
class CustomerChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_order_id',
        'field_name',
        'old_value',
        'new_value',
        'changed_by',
        'reason',
    ];

    // Relationships

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
