<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends FormRequest
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
        $customer = $this->route('customer');
        $customerId = $customer instanceof \App\Models\Customer ? $customer->id : $customer;

        return [
            'customer_type' => ['required', Rule::in(['PF', 'PJ'])],
            'document' => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers', 'document')
                    ->where('tenant_id', $this->user()?->tenant_id)
                    ->ignore($customerId),
            ],
            'state_registration' => ['nullable', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string', 'max:255'],
            'address_number' => ['nullable', 'string', 'max:20'],
            'address_complement' => ['nullable', 'string', 'max:100'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'city_code' => ['nullable', 'string', 'max:10'],
            'state' => ['nullable', 'string', 'size:2'],
            'country' => ['nullable', 'string', 'max:50'],
            'country_code' => ['nullable', 'string', 'max:10'],
            'birth_date' => ['nullable', 'date'],
            'observations' => ['nullable', 'string', 'max:2000'],
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
            'customer_type' => 'tipo de cliente',
            'document' => 'CPF/CNPJ',
            'state_registration' => 'inscrição estadual',
            'name' => 'nome',
            'trade_name' => 'nome fantasia',
            'email' => 'e-mail',
            'phone' => 'telefone',
            'phone_secondary' => 'telefone secundário',
            'mobile' => 'celular',
            'postal_code' => 'CEP',
            'address' => 'endereço',
            'address_number' => 'número',
            'address_complement' => 'complemento',
            'neighborhood' => 'bairro',
            'city' => 'cidade',
            'city_code' => 'código da cidade',
            'state' => 'estado',
            'country' => 'país',
            'birth_date' => 'data de nascimento',
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
            'document.unique' => 'Já existe um cliente cadastrado com este CPF/CNPJ.',
        ];
    }
}
