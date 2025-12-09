<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManufacturerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by Gate in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tenant_id' => ['required', 'integer', Rule::exists('tenants', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'document' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('manufacturers')->ignore($this->route('manufacturer')),
            ],
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'logo_url' => ['nullable', 'string', 'url', 'max:500'],
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
            'tenant_id' => 'tenant',
            'name' => 'nome',
            'document' => 'CNPJ',
            'email' => 'e-mail',
            'phone' => 'telefone',
            'website' => 'site',
            'logo_url' => 'URL do logo',
            'is_active' => 'status',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}
