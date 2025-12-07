<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property string|null $document
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website
 * @property string|null $tracking_url_template
 * @property string|null $api_endpoint
 * @property array<array-key, mixed>|null $api_credentials
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Shipment> $shipments
 * @property-read int|null $shipments_count
 * @property-read Tenant $tenant
 *
 * @method static Builder<static>|Carrier forTenant(int $tenantId)
 * @method static Builder<static>|Carrier newModelQuery()
 * @method static Builder<static>|Carrier newQuery()
 * @method static Builder<static>|Carrier query()
 * @method static Builder<static>|Carrier whereApiCredentials($value)
 * @method static Builder<static>|Carrier whereApiEndpoint($value)
 * @method static Builder<static>|Carrier whereCreatedAt($value)
 * @method static Builder<static>|Carrier whereDocument($value)
 * @method static Builder<static>|Carrier whereEmail($value)
 * @method static Builder<static>|Carrier whereId($value)
 * @method static Builder<static>|Carrier whereIsActive($value)
 * @method static Builder<static>|Carrier whereName($value)
 * @method static Builder<static>|Carrier wherePhone($value)
 * @method static Builder<static>|Carrier whereTenantId($value)
 * @method static Builder<static>|Carrier whereTrackingUrlTemplate($value)
 * @method static Builder<static>|Carrier whereUpdatedAt($value)
 * @method static Builder<static>|Carrier whereWebsite($value)
 *
 * @mixin \Eloquent
 */
class Carrier extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'document',
        'phone',
        'email',
        'website',
        'tracking_url_template',
        'api_endpoint',
        'api_credentials',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'api_credentials' => 'encrypted:array',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Get the tracking URL for a given tracking code.
     */
    public function getTrackingUrl(string $trackingCode): ?string
    {
        if (! $this->tracking_url_template) {
            return null;
        }

        return str_replace('{tracking_code}', $trackingCode, $this->tracking_url_template);
    }
}
