<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExchangeStatus extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'exchange_statuses';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'color',
        'sort_order',
        'is_final',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_final' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function exchanges(): HasMany
    {
        return $this->hasMany(Exchange::class, 'status_id');
    }
}
