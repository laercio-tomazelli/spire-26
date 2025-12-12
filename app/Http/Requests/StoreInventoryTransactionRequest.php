<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'part_id' => ['required', 'exists:parts,id'],
            'transaction_type_id' => ['required', 'exists:transaction_types,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document_number' => ['nullable', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'observations' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'warehouse_id' => 'depósito',
            'part_id' => 'peça',
            'transaction_type_id' => 'tipo de movimentação',
            'document_type_id' => 'tipo de documento',
            'document_number' => 'número do documento',
            'quantity' => 'quantidade',
            'unit_price' => 'preço unitário',
            'cost_price' => 'custo unitário',
            'observations' => 'observações',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'quantity.min' => 'A quantidade deve ser no mínimo 1.',
        ];
    }
}
