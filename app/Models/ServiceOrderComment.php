<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
