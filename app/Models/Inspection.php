<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InspectionStatus;
use App\Models\Concerns\BelongsToTenant;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $claim_number
 * @property Carbon|null $reference_date
 * @property int|null $tat_days
 * @property string $customer_name
 * @property string|null $customer_contact
 * @property string|null $product
 * @property bool $has_electrical_damage
 * @property int $reports_count
 * @property Carbon|null $contact_date
 * @property Carbon|null $inspection_date
 * @property string|null $inspection_time
 * @property Carbon|null $report_sent_at
 * @property InspectionStatus $status
 * @property int|null $assigned_to_user_id
 * @property string|null $remark
 * @property int|null $created_by_user_id
 * @property-read User|null $assignedTo
 * @property-read User|null $createdBy
 */
class Inspection extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'claim_number',
        'reference_date',
        'tat_days',
        'customer_name',
        'customer_contact',
        'product',
        'has_electrical_damage',
        'reports_count',
        'contact_date',
        'inspection_date',
        'inspection_time',
        'report_sent_at',
        'status',
        'assigned_to_user_id',
        'remark',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'reference_date' => 'date',
            'contact_date' => 'date',
            'inspection_date' => 'date',
            'report_sent_at' => 'date',
            'has_electrical_damage' => 'boolean',
            'status' => InspectionStatus::class,
        ];
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Verifica se o usuário possui conflito de horário em data/hora especificada.
     */
    public static function hasUserConflict(
        int $userId,
        CarbonInterface|string $date,
        ?string $time,
        ?int $ignoreId = null,
    ): bool {
        if ($time === null || $time === '') {
            return false;
        }

        return self::query()
            ->where('assigned_to_user_id', $userId)
            ->whereDate('inspection_date', $date)
            ->where('inspection_time', $time)
            ->when($ignoreId, fn (Builder $q, int $id) => $q->where('id', '!=', $id))
            ->exists();
    }
}
