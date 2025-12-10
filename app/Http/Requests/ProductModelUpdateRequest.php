<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductModelUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productModelId = $this->route('product_model')?->id;

        return [
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'product_category_id' => ['nullable', 'integer', 'exists:product_categories,id'],
            'model_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('product_models', 'model_code')->ignore($productModelId),
            ],
            'model_name' => ['nullable', 'string', 'max:255'],
            'manufacturer_model' => ['nullable', 'string', 'max:255'],
            'ean' => ['nullable', 'string', 'max:20'],
            'release_date' => ['nullable', 'date'],
            'end_of_life_date' => ['nullable', 'date', 'after_or_equal:release_date'],
            'warranty_months' => ['nullable', 'integer', 'min:0', 'max:999'],
            'promotional_warranty_months' => ['nullable', 'integer', 'min:0', 'max:999'],
            'observations' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['boolean'],
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
            'brand_id' => 'marca',
            'product_category_id' => 'categoria',
            'model_code' => 'código do modelo',
            'model_name' => 'nome do modelo',
            'manufacturer_model' => 'modelo do fabricante',
            'ean' => 'EAN',
            'release_date' => 'data de lançamento',
            'end_of_life_date' => 'data de fim de vida',
            'warranty_months' => 'garantia (meses)',
            'promotional_warranty_months' => 'garantia promocional (meses)',
            'observations' => 'observações',
            'is_active' => 'ativo',
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
            'model_code.unique' => 'Já existe um modelo com este código.',
            'end_of_life_date.after_or_equal' => 'A data de fim de vida deve ser igual ou posterior à data de lançamento.',
        ];
    }
}
