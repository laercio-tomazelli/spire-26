<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
