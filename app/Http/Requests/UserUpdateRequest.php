<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
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
        /** @var User $user */
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'username' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'user_type' => ['required', new Enum(UserType::class)],
            'is_active' => ['boolean'],
            'is_partner_admin' => ['boolean'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],

            // Relações - obrigatórias dependendo do tipo
            'tenant_id' => [
                'nullable',
                'integer',
                Rule::exists('tenants', 'id'),
                Rule::requiredIf(fn (): bool => $this->input('user_type') == UserType::SpireClient->value),
            ],
            'partner_id' => [
                'nullable',
                'integer',
                Rule::exists('partners', 'id'),
                Rule::requiredIf(fn (): bool => $this->input('user_type') === UserType::Partner->value),
            ],
            'manufacturer_id' => [
                'nullable',
                'integer',
                Rule::exists('manufacturers', 'id'),
                Rule::requiredIf(fn (): bool => $this->input('user_type') === UserType::Manufacturer->value),
            ],
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
            'email' => 'e-mail',
            'username' => 'usuário',
            'password' => 'senha',
            'user_type' => 'tipo de usuário',
            'is_active' => 'status',
            'is_partner_admin' => 'administrador do posto',
            'phone' => 'telefone',
            'mobile' => 'celular',
            'tenant_id' => 'tenant',
            'partner_id' => 'posto autorizado',
            'manufacturer_id' => 'fabricante',
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
            'partner_id.required_if' => 'O posto autorizado é obrigatório para usuários do tipo Partner.',
            'manufacturer_id.required_if' => 'O fabricante é obrigatório para usuários do tipo Fabricante.',
            'tenant_id.required_if' => 'O tenant é obrigatório para usuários do tipo Cliente Spire.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'is_partner_admin' => $this->boolean('is_partner_admin', false),
        ]);
    }
}
