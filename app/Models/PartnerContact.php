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
 * @property int $partner_id
 * @property int|null $contact_type_id
 * @property string|null $name
 * @property string|null $phone
 * @property string|null $email
 * @property bool $is_primary
 * @property string|null $observations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Partner $partner
 *
 * @method static Builder<static>|PartnerContact newModelQuery()
 * @method static Builder<static>|PartnerContact newQuery()
 * @method static Builder<static>|PartnerContact query()
 * @method static Builder<static>|PartnerContact whereContactTypeId($value)
 * @method static Builder<static>|PartnerContact whereCreatedAt($value)
 * @method static Builder<static>|PartnerContact whereEmail($value)
 * @method static Builder<static>|PartnerContact whereId($value)
 * @method static Builder<static>|PartnerContact whereIsPrimary($value)
 * @method static Builder<static>|PartnerContact whereName($value)
 * @method static Builder<static>|PartnerContact whereObservations($value)
 * @method static Builder<static>|PartnerContact wherePartnerId($value)
 * @method static Builder<static>|PartnerContact wherePhone($value)
 * @method static Builder<static>|PartnerContact whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PartnerContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'name',
        'role',
        'email',
        'phone',
        'mobile',
        'is_primary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
