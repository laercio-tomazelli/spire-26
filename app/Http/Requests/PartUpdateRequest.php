<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PartUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'part_code' => [
                'required',
                'string',
                'max:60',
                Rule::unique('parts')->where(fn ($query) => $query->where('tenant_id', Auth::user()->tenant_id))->ignore($this->route('part')),
            ],
            'description' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:10'],
            'ncm' => ['nullable', 'string', 'max:15'],
            'cest' => ['nullable', 'string', 'max:15'],
            'origin' => ['nullable', 'integer', 'min:0', 'max:9'],
            'ean' => ['nullable', 'string', 'max:20'],
            'ean_packaging' => ['nullable', 'string', 'max:20'],
            'manufacturer_code' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'net_weight' => ['nullable', 'numeric', 'min:0'],
            'gross_weight' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'depth' => ['nullable', 'numeric', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'max_stock' => ['nullable', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:100'],
            'is_display' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
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
            'part_code' => 'código',
            'description' => 'descrição',
            'short_description' => 'descrição curta',
            'unit' => 'unidade',
            'ncm' => 'NCM',
            'cest' => 'CEST',
            'origin' => 'origem',
            'ean' => 'EAN',
            'ean_packaging' => 'EAN embalagem',
            'manufacturer_code' => 'código do fabricante',
            'price' => 'preço',
            'cost_price' => 'preço de custo',
            'net_weight' => 'peso líquido',
            'gross_weight' => 'peso bruto',
            'width' => 'largura',
            'height' => 'altura',
            'depth' => 'profundidade',
            'min_stock' => 'estoque mínimo',
            'max_stock' => 'estoque máximo',
            'location' => 'localização',
            'is_display' => 'é display',
            'is_active' => 'ativo',
        ];
    }
}
