<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\UserType;
use App\Models\Manufacturer;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;
use Override;

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
                Rule::requiredIf(fn (): bool => in_array($this->input('user_type'), [
                    UserType::SpireClient->value,
                    UserType::Partner->value,
                    UserType::Manufacturer->value,
                ], true)),
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
     * Add tenant and entity boundary validation after basic field validation.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $authUser = $this->user();

            if (! $authUser instanceof User) {
                return;
            }

            $type = $this->requestedUserType();

            if (! $type instanceof UserType) {
                return;
            }

            /** @var User $targetUser */
            $targetUser = $this->route('user');

            $this->validateActorScope($validator, $authUser, $targetUser, $type);
            $this->validateEntityTenant($validator, $type);
        });
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    #[Override]
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
    #[Override]
    public function messages(): array
    {
        return [
            'partner_id.required_if' => 'O posto autorizado é obrigatório para usuários do tipo Partner.',
            'manufacturer_id.required_if' => 'O fabricante é obrigatório para usuários do tipo Fabricante.',
            'tenant_id.required_if' => 'O tenant é obrigatório para este tipo de usuário.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    #[Override]
    protected function prepareForValidation(): void
    {
        $input = [
            'is_active' => $this->boolean('is_active', true),
            'is_partner_admin' => $this->boolean('is_partner_admin', false),
        ];

        if (empty($this->input('tenant_id'))) {
            $tenantId = $this->tenantIdFromSelectedEntity();

            if ($tenantId !== null) {
                $input['tenant_id'] = $tenantId;
            }
        }

        $this->merge($input);
    }

    private function requestedUserType(): ?UserType
    {
        $type = $this->input('user_type');

        if (! is_string($type)) {
            return null;
        }

        return UserType::tryFrom($type);
    }

    private function tenantIdFromSelectedEntity(): ?int
    {
        $type = $this->requestedUserType();

        return match ($type) {
            UserType::Partner => $this->selectedPartner()?->tenant_id,
            UserType::Manufacturer => $this->selectedManufacturer()?->tenant_id,
            default => null,
        };
    }

    private function validateActorScope(Validator $validator, User $authUser, User $targetUser, UserType $type): void
    {
        if ($authUser->isSpire()) {
            return;
        }

        if ($type !== $targetUser->user_type) {
            $validator->errors()->add('user_type', 'Você não pode alterar o tipo deste usuário.');
        }

        if ((int) $this->input('tenant_id') !== $targetUser->tenant_id) {
            $validator->errors()->add('tenant_id', 'Você não pode alterar o tenant deste usuário.');
        }

        if ((int) $this->input('partner_id') !== (int) $targetUser->partner_id) {
            $validator->errors()->add('partner_id', 'Você não pode alterar o posto autorizado deste usuário.');
        }

        if ((int) $this->input('manufacturer_id') !== (int) $targetUser->manufacturer_id) {
            $validator->errors()->add('manufacturer_id', 'Você não pode alterar o fabricante deste usuário.');
        }

        if ($this->boolean('is_partner_admin') !== $targetUser->is_partner_admin) {
            $validator->errors()->add('is_partner_admin', 'Você não pode alterar o nível administrativo deste usuário.');
        }

        if ($this->boolean('is_active') !== $targetUser->is_active) {
            $validator->errors()->add('is_active', 'Você não pode alterar o status deste usuário.');
        }

        if ($authUser->isPartnerAdmin()) {
            if ($type !== UserType::Partner) {
                $validator->errors()->add('user_type', 'Administradores de posto só podem editar usuários do próprio posto.');
            }

            if ((int) $this->input('partner_id') !== $authUser->partner_id) {
                $validator->errors()->add('partner_id', 'O posto autorizado informado não pertence ao seu escopo.');
            }

            if ((int) $this->input('tenant_id') !== $authUser->tenant_id) {
                $validator->errors()->add('tenant_id', 'O tenant informado não pertence ao seu escopo.');
            }
        }
    }

    private function validateEntityTenant(Validator $validator, UserType $type): void
    {
        $tenantId = (int) $this->input('tenant_id');

        if ($type === UserType::Partner) {
            $partner = $this->selectedPartner();

            if ($partner && $partner->tenant_id !== $tenantId) {
                $validator->errors()->add('tenant_id', 'O tenant informado não pertence ao posto autorizado selecionado.');
            }
        }

        if ($type === UserType::Manufacturer) {
            $manufacturer = $this->selectedManufacturer();

            if ($manufacturer && $manufacturer->tenant_id !== $tenantId) {
                $validator->errors()->add('tenant_id', 'O tenant informado não pertence ao fabricante selecionado.');
            }
        }
    }

    private function selectedPartner(): ?Partner
    {
        $partnerId = $this->input('partner_id');

        if (! is_numeric($partnerId)) {
            return null;
        }

        return Partner::withoutGlobalScope('tenant')->find((int) $partnerId);
    }

    private function selectedManufacturer(): ?Manufacturer
    {
        $manufacturerId = $this->input('manufacturer_id');

        if (! is_numeric($manufacturerId)) {
            return null;
        }

        return Manufacturer::withoutGlobalScope('tenant')->find((int) $manufacturerId);
    }
}
