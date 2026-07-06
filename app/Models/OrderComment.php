<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $id
 * @property int $order_id
 * @property int|null $user_id
 * @property string $comment
 * @property string $comment_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @property-read User|null $user
 *
 * @method static Builder<static>|OrderComment newModelQuery()
 * @method static Builder<static>|OrderComment newQuery()
 * @method static Builder<static>|OrderComment query()
 * @method static Builder<static>|OrderComment whereComment($value)
 * @method static Builder<static>|OrderComment whereCommentType($value)
 * @method static Builder<static>|OrderComment whereCreatedAt($value)
 * @method static Builder<static>|OrderComment whereId($value)
 * @method static Builder<static>|OrderComment whereOrderId($value)
 * @method static Builder<static>|OrderComment whereUpdatedAt($value)
 * @method static Builder<static>|OrderComment whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[Fillable([
    'order_id',
    'user_id',
    'content',
    'is_internal',
])]
class OrderComment extends Model
{
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
        ];
    }

    // Relationships

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
