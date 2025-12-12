<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceOrderUpdateRequest extends FormRequest
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
            // Dados do Cliente
            'customer_id' => ['nullable', 'exists:customers,id'],

            // Dados do Posto
            'partner_id' => ['nullable', 'exists:partners,id'],

            // Dados do Produto
            'brand_id' => ['sometimes', 'required', 'exists:brands,id'],
            'product_model_id' => ['nullable', 'exists:product_models,id'],
            'model_received' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],

            // Dados da Compra
            'retailer_name' => ['nullable', 'string', 'max:255'],
            'purchase_invoice_number' => ['nullable', 'string', 'max:255'],
            'purchase_invoice_date' => ['nullable', 'date'],
            'purchase_value' => ['nullable', 'numeric', 'min:0'],

            // Classificação
            'service_location_id' => ['nullable', 'exists:service_locations,id'],
            'service_order_type_id' => ['nullable', 'exists:service_order_types,id'],
            'service_type_id' => ['nullable', 'exists:service_types,id'],
            'repair_type_id' => ['nullable', 'exists:repair_types,id'],
            'warranty_type' => ['nullable', 'in:in_warranty,out_of_warranty'],

            // Status
            'status_id' => ['nullable', 'exists:service_order_statuses,id'],
            'sub_status_id' => ['nullable', 'exists:service_order_sub_statuses,id'],
            'priority_id' => ['nullable', 'exists:service_order_priorities,id'],

            // Defeito e Reparo
            'reported_defect' => ['nullable', 'string'],
            'confirmed_defect' => ['nullable', 'string'],
            'symptom' => ['nullable', 'string', 'max:100'],
            'defect_id' => ['nullable', 'exists:defects,id'],
            'defect_found_id' => ['nullable', 'exists:defects,id'],
            'solution_id' => ['nullable', 'exists:solutions,id'],
            'repair_description' => ['nullable', 'string'],
            'solution_description' => ['nullable', 'string'],

            // Acessórios e Condições
            'accessories' => ['nullable', 'string', 'max:255'],
            'conditions' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string'],
            'internal_notes' => ['nullable', 'string'],

            // Referências Externas
            'protocol' => ['nullable', 'string', 'max:50'],
            'manufacturer_pre_order' => ['nullable', 'string', 'max:50'],
            'manufacturer_order' => ['nullable', 'string', 'max:100'],
            'partner_order' => ['nullable', 'string', 'max:100'],
            'external_id' => ['nullable', 'string', 'max:100'],

            // Custos
            'labor_cost' => ['nullable', 'numeric', 'min:0'],
            'km_cost' => ['nullable', 'numeric', 'min:0'],
            'extra_cost' => ['nullable', 'numeric', 'min:0'],
            'distance_km' => ['nullable', 'integer', 'min:0'],

            // Logística de Entrada
            'entry_invoice_number' => ['nullable', 'string', 'max:50'],
            'entry_invoice_date' => ['nullable', 'date'],
            'entry_tracking_code' => ['nullable', 'string', 'max:50'],
            'received_at' => ['nullable', 'date'],
            'received_serial' => ['nullable', 'string', 'max:255'],

            // Logística de Saída
            'exit_invoice_number' => ['nullable', 'string', 'max:50'],
            'exit_invoice_date' => ['nullable', 'date'],
            'exit_tracking_code' => ['nullable', 'string', 'max:50'],
            'exit_sent_at' => ['nullable', 'date'],
            'delivered_at' => ['nullable', 'date'],

            // Agendamento
            'scheduled_visit_date' => ['nullable', 'date'],
            'technician_id' => ['nullable', 'exists:users,id'],

            // Flags
            'is_critical' => ['nullable', 'boolean'],
            'is_no_defect' => ['nullable', 'boolean'],
            'is_reentry' => ['nullable', 'boolean'],

            // Troca
            'is_exchange' => ['nullable', 'boolean'],
            'exchange_type' => ['nullable', 'in:product,refund'],
            'exchange_reason' => ['nullable', 'string'],
            'exchange_negotiated_value' => ['nullable', 'numeric', 'min:0'],
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
            'brand_id.required' => 'A marca é obrigatória.',
            'brand_id.exists' => 'A marca selecionada é inválida.',
            'customer_id.exists' => 'O cliente selecionado é inválido.',
            'partner_id.exists' => 'O posto selecionado é inválido.',
            'status_id.exists' => 'O status selecionado é inválido.',
            'labor_cost.numeric' => 'O custo de mão de obra deve ser um número.',
            'labor_cost.min' => 'O custo de mão de obra não pode ser negativo.',
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
            'customer_id' => 'cliente',
            'partner_id' => 'posto autorizado',
            'brand_id' => 'marca',
            'product_model_id' => 'modelo do produto',
            'serial_number' => 'número de série',
            'reported_defect' => 'defeito relatado',
            'confirmed_defect' => 'defeito confirmado',
            'labor_cost' => 'mão de obra',
            'km_cost' => 'custo de quilometragem',
            'extra_cost' => 'custos extras',
        ];
    }
}
