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
 * @property int $service_order_id
 * @property int|null $user_id
 * @property string|null $username
 * @property string $comment
 * @property string $comment_type
 * @property int|null $privacy_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ServiceOrder $serviceOrder
 * @property-read User|null $user
 *
 * @method static Builder<static>|ServiceOrderComment newModelQuery()
 * @method static Builder<static>|ServiceOrderComment newQuery()
 * @method static Builder<static>|ServiceOrderComment query()
 * @method static Builder<static>|ServiceOrderComment whereComment($value)
 * @method static Builder<static>|ServiceOrderComment whereCommentType($value)
 * @method static Builder<static>|ServiceOrderComment whereCreatedAt($value)
 * @method static Builder<static>|ServiceOrderComment whereId($value)
 * @method static Builder<static>|ServiceOrderComment wherePrivacyId($value)
 * @method static Builder<static>|ServiceOrderComment whereServiceOrderId($value)
 * @method static Builder<static>|ServiceOrderComment whereUpdatedAt($value)
 * @method static Builder<static>|ServiceOrderComment whereUserId($value)
 * @method static Builder<static>|ServiceOrderComment whereUsername($value)
 *
 * @mixin \Eloquent
 */
class ServiceOrderComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'user_id',
        'type',
        'content',
        'is_internal',
        'is_pinned',
    ];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
            'is_pinned' => 'boolean',
        ];
    }

    // Relationships

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
