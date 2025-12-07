<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $formatted_file_size
 * @property-read ServiceOrder|null $serviceOrder
 * @property-read User|null $uploadedByUser
 *
 * @method static Builder<static>|ServiceOrderEvidence newModelQuery()
 * @method static Builder<static>|ServiceOrderEvidence newQuery()
 * @method static Builder<static>|ServiceOrderEvidence query()
 *
 * @mixin \Eloquent
 */
class ServiceOrderEvidence extends Model
{
    use HasFactory;

    protected $table = 'service_order_evidence';

    protected $fillable = [
        'service_order_id',
        'uploaded_by',
        'type',
        'category',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    // Relationships

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Helpers

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'video/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Get human-readable file size.
     */
    protected function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' bytes';
    }
}
