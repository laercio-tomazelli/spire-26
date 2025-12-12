<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string|null $code_range
 * @property string $state
 * @property string $city
 * @property string|null $street
 * @property string|null $complement
 * @property string|null $neighborhood
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PostalCode extends Model
{
    protected $fillable = [
        'code',
        'code_range',
        'state',
        'city',
        'street',
        'complement',
        'neighborhood',
    ];

    /**
     * Formata o CEP com máscara (XXXXX-XXX)
     */
    protected function getFormattedCodeAttribute(): string
    {
        $code = str_pad($this->code, 8, '0', STR_PAD_LEFT);

        return substr($code, 0, 5).'-'.substr($code, 5, 3);
    }
}
