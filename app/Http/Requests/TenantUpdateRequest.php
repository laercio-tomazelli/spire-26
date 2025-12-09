<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'document' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('tenants')->ignore($this->route('tenant')),
            ],
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:2'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:100'],
            'logo_url' => ['nullable', 'string', 'url', 'max:500'],
            'settings' => ['nullable', 'array'],
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
            'name' => 'nome',
            'document' => 'CNPJ',
            'email' => 'e-mail',
            'phone' => 'telefone',
            'address' => 'endereço',
            'city' => 'cidade',
            'state' => 'estado',
            'postal_code' => 'CEP',
            'country' => 'país',
            'logo_url' => 'URL do logo',
            'settings' => 'configurações',
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
