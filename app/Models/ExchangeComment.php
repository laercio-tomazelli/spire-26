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
 * @property int $exchange_id
 * @property int|null $user_id
 * @property string|null $username
 * @property string $comment
 * @property string $comment_type
 * @property int|null $privacy_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Exchange $exchange
 * @property-read User|null $user
 *
 * @method static Builder<static>|ExchangeComment newModelQuery()
 * @method static Builder<static>|ExchangeComment newQuery()
 * @method static Builder<static>|ExchangeComment query()
 * @method static Builder<static>|ExchangeComment whereComment($value)
 * @method static Builder<static>|ExchangeComment whereCommentType($value)
 * @method static Builder<static>|ExchangeComment whereCreatedAt($value)
 * @method static Builder<static>|ExchangeComment whereExchangeId($value)
 * @method static Builder<static>|ExchangeComment whereId($value)
 * @method static Builder<static>|ExchangeComment wherePrivacyId($value)
 * @method static Builder<static>|ExchangeComment whereUpdatedAt($value)
 * @method static Builder<static>|ExchangeComment whereUserId($value)
 * @method static Builder<static>|ExchangeComment whereUsername($value)
 *
 * @mixin \Eloquent
 */
class ExchangeComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'exchange_id',
        'user_id',
        'content',
        'is_internal',
    ];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
        ];
    }

    // Relationships

    public function exchange(): BelongsTo
    {
        return $this->belongsTo(Exchange::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
