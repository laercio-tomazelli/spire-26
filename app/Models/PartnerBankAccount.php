<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $formatted_account
 * @property-read string $formatted_agency
 * @property-read Partner|null $partner
 *
 * @method static Builder<static>|PartnerBankAccount newModelQuery()
 * @method static Builder<static>|PartnerBankAccount newQuery()
 * @method static Builder<static>|PartnerBankAccount query()
 *
 * @mixin \Eloquent
 */
class PartnerBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'bank_name',
        'bank_code',
        'agency',
        'agency_digit',
        'account_number',
        'account_digit',
        'account_type',
        'holder_name',
        'holder_document',
        'pix_key',
        'pix_key_type',
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

    /**
     * Get the formatted account number.
     */
    protected function getFormattedAccountAttribute(): string
    {
        $account = $this->account_number;
        if ($this->account_digit) {
            $account .= '-'.$this->account_digit;
        }

        return $account;
    }

    /**
     * Get the formatted agency.
     */
    protected function getFormattedAgencyAttribute(): string
    {
        $agency = $this->agency;
        if ($this->agency_digit) {
            $agency .= '-'.$this->agency_digit;
        }

        return $agency;
    }
}
