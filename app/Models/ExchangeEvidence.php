<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @property-read Exchange|null $exchange
 * @property-read User|null $uploadedByUser
 *
 * @method static Builder<static>|ExchangeEvidence newModelQuery()
 * @method static Builder<static>|ExchangeEvidence newQuery()
 * @method static Builder<static>|ExchangeEvidence query()
 *
 * @mixin \Eloquent
 */
#[Fillable([
    'exchange_id',
    'uploaded_by',
    'type',
    'file_name',
    'file_path',
    'file_size',
    'mime_type',
    'description',
])]
#[Table(name: 'exchange_evidence')]
class ExchangeEvidence extends Model
{
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    // Relationships

    public function exchange(): BelongsTo
    {
        return $this->belongsTo(Exchange::class);
    }

    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
