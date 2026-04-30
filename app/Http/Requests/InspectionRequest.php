<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\InspectionStatus;
use App\Models\Inspection;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class InspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $inspection = $this->route('inspection');
        $inspectionId = $inspection instanceof Inspection ? $inspection->id : null;
        $tenantId = $this->user()?->tenant_id;

        return [
            'claim_number' => [
                'required', 'string', 'max:30',
                Rule::unique('inspections', 'claim_number')
                    ->where(fn ($q) => $q->where('tenant_id', $tenantId)->whereNull('deleted_at'))
                    ->ignore($inspectionId),
            ],
            'reference_date' => ['nullable', 'date'],
            'tat_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_contact' => ['nullable', 'string', 'max:50'],
            'product' => ['nullable', 'string', 'max:100'],
            'has_electrical_damage' => ['boolean'],
            'reports_count' => ['nullable', 'integer', 'min:0', 'max:99'],
            'contact_date' => ['nullable', 'date'],
            'inspection_date' => ['nullable', 'date'],
            'inspection_time' => ['nullable', 'date_format:H:i'],
            'report_sent_at' => ['nullable', 'date'],
            'status' => ['required', new Enum(InspectionStatus::class)],
            'assigned_to_user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('tenant_id', $tenantId)),
            ],
            'remark' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'claim_number.unique' => 'Já existe uma vistoria com este número de sinistro.',
            'inspection_time.date_format' => 'A hora deve estar no formato HH:MM.',
        ];
    }

    /**
     * Validação extra: analista disponível na data/hora.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v): void {
            $userId = $this->input('assigned_to_user_id');
            $date = $this->input('inspection_date');
            $time = $this->input('inspection_time');

            if (! $userId || ! $date || ! $time) {
                return;
            }

            $inspection = $this->route('inspection');
            $ignoreId = $inspection instanceof Inspection ? $inspection->id : null;

            if (Inspection::hasUserConflict((int) $userId, $date, $time, $ignoreId)) {
                /** @var User|null $user */
                $user = User::find($userId);
                $name = $user->name ?? 'Analista';
                $v->errors()->add(
                    'assigned_to_user_id',
                    "{$name} já possui uma vistoria agendada para esta data e horário.",
                );
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_electrical_damage' => $this->boolean('has_electrical_damage'),
        ]);
    }
}
