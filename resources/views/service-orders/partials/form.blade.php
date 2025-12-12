{{-- Formulário de Ordem de Serviço com Seções --}}
@php
    $isEdit = isset($serviceOrder) && $serviceOrder !== null;
@endphp

<div class="space-y-6">
    {{-- Seção: Dados Básicos --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dados da OS</h3>
        </x-slot:header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Posto Autorizado --}}
            <div>
                <label for="partner_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Posto Autorizado
                </label>
                <x-spire::select id="partner_id" name="partner_id" :options="$partners->map(fn($p) => ['value' => $p->id, 'label' => $p->trade_name])->toArray()" :value="old('partner_id', $serviceOrder?->partner_id)"
                    placeholder="Selecione..." />
                @error('partner_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Marca --}}
            <div>
                <label for="brand_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Marca <span class="text-red-500">*</span>
                </label>
                <x-spire::select id="brand_id" name="brand_id" :options="$brands->map(fn($b) => ['value' => $b->id, 'label' => $b->name])->toArray()" :value="old('brand_id', $serviceOrder?->brand_id)"
                    placeholder="Selecione..." required />
                @error('brand_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Status
                </label>
                <x-spire::select id="status_id" name="status_id" :options="$statuses->map(fn($s) => ['value' => $s->id, 'label' => $s->name])->toArray()" :value="old('status_id', $serviceOrder?->status_id)"
                    placeholder="Selecione..." />
                @error('status_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipo de Serviço --}}
            <div>
                <label for="service_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tipo de Serviço
                </label>
                <x-spire::select id="service_type_id" name="service_type_id" :options="$serviceTypes->map(fn($s) => ['value' => $s->id, 'label' => $s->name])->toArray()" :value="old('service_type_id', $serviceOrder?->service_type_id)"
                    placeholder="Selecione..." />
                @error('service_type_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Local do Serviço --}}
            <div>
                <label for="service_location_id"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Local do Serviço
                </label>
                <x-spire::select id="service_location_id" name="service_location_id" :options="$serviceLocations->map(fn($s) => ['value' => $s->id, 'label' => $s->name])->toArray()" :value="old('service_location_id', $serviceOrder?->service_location_id)"
                    placeholder="Selecione..." />
                @error('service_location_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Garantia --}}
            <div>
                <label for="warranty_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Garantia
                </label>
                <x-spire::select id="warranty_type" name="warranty_type" :options="[
                    ['value' => 'in_warranty', 'label' => 'Em Garantia'],
                    ['value' => 'out_of_warranty', 'label' => 'Fora de Garantia'],
                ]" :value="old('warranty_type', $serviceOrder?->warranty_type)"
                    placeholder="Selecione..." />
                @error('warranty_type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Referências Externas --}}
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Referências Externas</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="protocol" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Protocolo
                    </label>
                    <x-spire::input id="protocol" name="protocol" :value="old('protocol', $serviceOrder?->protocol)"
                        placeholder="Número do protocolo" />
                </div>

                <div>
                    <label for="manufacturer_pre_order"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Pré-OS Fabricante
                    </label>
                    <x-spire::input id="manufacturer_pre_order" name="manufacturer_pre_order" :value="old('manufacturer_pre_order', $serviceOrder?->manufacturer_pre_order)" />
                </div>

                <div>
                    <label for="manufacturer_order"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        OS Fabricante
                    </label>
                    <x-spire::input id="manufacturer_order" name="manufacturer_order" :value="old('manufacturer_order', $serviceOrder?->manufacturer_order)" />
                </div>

                <div>
                    <label for="partner_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        OS Posto
                    </label>
                    <x-spire::input id="partner_order" name="partner_order" :value="old('partner_order', $serviceOrder?->partner_order)" />
                </div>
            </div>
        </div>
    </x-spire::card>

    {{-- Seção: Consumidor --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Consumidor</h3>
        </x-slot:header>

        <div class="grid grid-cols-1 gap-4">
            {{-- Busca de Cliente --}}
            <div>
                <label for="customer_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Buscar Cliente
                </label>
                <div class="relative">
                    <x-spire::input id="customer_search" type="text"
                        placeholder="Digite o nome, CPF/CNPJ ou telefone do cliente..." autocomplete="off" />
                    <input type="hidden" name="customer_id" id="customer_id"
                        value="{{ old('customer_id', $serviceOrder?->customer_id) }}">
                </div>
                @error('customer_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cliente Selecionado --}}
            <div id="selected-customer"
                class="{{ old('customer_id', $serviceOrder?->customer_id) ? '' : 'hidden' }} p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                @if ($isEdit && $serviceOrder->customer)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <x-spire::avatar size="md" :name="$serviceOrder->customer->name" />
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $serviceOrder->customer->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $serviceOrder->customer->formatted_document }}</p>
                            </div>
                        </div>
                        <button type="button" id="clear-customer"
                            class="text-red-600 hover:text-red-700 dark:text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <div class="flex gap-2">
                <x-spire::button type="button" variant="secondary" size="sm"
                    onclick="window.open('{{ route('customers.create') }}', '_blank')">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Novo Cliente
                </x-spire::button>
            </div>
        </div>
    </x-spire::card>

    {{-- Seção: Produto --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Produto</h3>
        </x-slot:header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Modelo Recebido --}}
            <div>
                <label for="model_received" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Modelo Recebido
                </label>
                <x-spire::input id="model_received" name="model_received" :value="old('model_received', $serviceOrder?->model_received)"
                    placeholder="Modelo informado pelo cliente" />
            </div>

            {{-- Número de Série --}}
            <div>
                <label for="serial_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Número de Série
                </label>
                <x-spire::input id="serial_number" name="serial_number" :value="old('serial_number', $serviceOrder?->serial_number)"
                    placeholder="S/N do produto" />
            </div>

            {{-- Modelo do Sistema --}}
            <div>
                <label for="product_model_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Modelo (Sistema)
                </label>
                <x-spire::select id="product_model_id" name="product_model_id" :options="[]" :value="old('product_model_id', $serviceOrder?->product_model_id)"
                    placeholder="Selecione a marca primeiro" />
            </div>
        </div>

        {{-- Dados da Compra --}}
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Dados da Compra</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="retailer_name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Loja/Varejista
                    </label>
                    <x-spire::input id="retailer_name" name="retailer_name" :value="old('retailer_name', $serviceOrder?->retailer_name)"
                        placeholder="Nome da loja" />
                </div>

                <div>
                    <label for="purchase_invoice_number"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nota Fiscal
                    </label>
                    <x-spire::input id="purchase_invoice_number" name="purchase_invoice_number" :value="old('purchase_invoice_number', $serviceOrder?->purchase_invoice_number)"
                        placeholder="Número da NF" />
                </div>

                <div>
                    <label for="purchase_invoice_date"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Data da Compra
                    </label>
                    <x-spire::input id="purchase_invoice_date" name="purchase_invoice_date" type="date"
                        :value="old(
                            'purchase_invoice_date',
                            $serviceOrder?->purchase_invoice_date
                                ? (is_string($serviceOrder->purchase_invoice_date)
                                    ? \Carbon\Carbon::parse($serviceOrder->purchase_invoice_date)->format('Y-m-d')
                                    : $serviceOrder->purchase_invoice_date->format('Y-m-d'))
                                : '',
                        )" />
                </div>

                <div>
                    <label for="purchase_value"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Valor da Compra
                    </label>
                    <x-spire::input id="purchase_value" name="purchase_value" type="text" :value="old(
                        'purchase_value',
                        $serviceOrder?->purchase_value > 0
                            ? number_format((float) $serviceOrder->purchase_value, 2, ',', '.')
                            : '',
                    )"
                        placeholder="0,00" data-mask="money" />
                </div>
            </div>
        </div>

        {{-- Acessórios e Condições --}}
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="accessories" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Acessórios
                    </label>
                    <x-spire::input id="accessories" name="accessories" :value="old('accessories', $serviceOrder?->accessories)"
                        placeholder="Ex: cabo, controle remoto, manual" />
                </div>

                <div>
                    <label for="conditions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Condições do Produto
                    </label>
                    <x-spire::input id="conditions" name="conditions" :value="old('conditions', $serviceOrder?->conditions)"
                        placeholder="Ex: arranhões, marcas de uso" />
                </div>
            </div>
        </div>
    </x-spire::card>

    {{-- Seção: Defeito e Reparo --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Defeito e Reparo</h3>
        </x-slot:header>

        <div class="grid grid-cols-1 gap-4">
            {{-- Defeito Relatado --}}
            <div>
                <label for="reported_defect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Defeito Relatado <span class="text-gray-400">(pelo cliente)</span>
                </label>
                <x-spire::textarea id="reported_defect" name="reported_defect" rows="3"
                    placeholder="Descreva o defeito relatado pelo cliente...">{{ old('reported_defect', $serviceOrder?->reported_defect) }}</x-spire::textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Condição do Defeito --}}
                <div>
                    <label for="defect_condition"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Condição do Defeito
                    </label>
                    <x-spire::select id="defect_condition" name="defect_condition" :options="[
                        ['value' => 'A', 'label' => 'A - DOA (Dead on Arrival)'],
                        ['value' => 'B', 'label' => 'B - Defeito de Fábrica'],
                        ['value' => 'C', 'label' => 'C - Mau Uso'],
                        ['value' => 'D', 'label' => 'D - Desgaste Natural'],
                    ]"
                        :value="old('defect_condition', $serviceOrder?->defect_condition)" placeholder="Selecione..." />
                </div>

                {{-- Sintoma --}}
                <div>
                    <label for="symptom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Sintoma
                    </label>
                    <x-spire::input id="symptom" name="symptom" :value="old('symptom', $serviceOrder?->symptom)"
                        placeholder="Sintoma identificado" />
                </div>
            </div>

            {{-- Defeito Confirmado --}}
            <div>
                <label for="confirmed_defect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Defeito Confirmado <span class="text-gray-400">(após avaliação)</span>
                </label>
                <x-spire::textarea id="confirmed_defect" name="confirmed_defect" rows="3"
                    placeholder="Descreva o defeito confirmado após avaliação técnica...">{{ old('confirmed_defect', $serviceOrder?->confirmed_defect) }}</x-spire::textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Tipo de Reparo --}}
                <div>
                    <label for="repair_type_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de Reparo
                    </label>
                    <x-spire::select id="repair_type_id" name="repair_type_id" :options="$repairTypes->map(fn($r) => ['value' => $r->id, 'label' => $r->name])->toArray()" :value="old('repair_type_id', $serviceOrder?->repair_type_id)"
                        placeholder="Selecione..." />
                </div>

                {{-- Sem Defeito? --}}
                <div class="flex items-center pt-6">
                    <x-spire::checkbox id="is_no_defect" name="is_no_defect" :checked="old('is_no_defect', $serviceOrder?->is_no_defect)" />
                    <label for="is_no_defect" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        Sem Defeito Constatado
                    </label>
                </div>
            </div>

            {{-- Descrição do Reparo --}}
            <div>
                <label for="repair_description"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Descrição do Reparo
                </label>
                <x-spire::textarea id="repair_description" name="repair_description" rows="3"
                    placeholder="Descreva o reparo realizado...">{{ old('repair_description', $serviceOrder?->repair_description) }}</x-spire::textarea>
            </div>

            {{-- Observações --}}
            <div>
                <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Observações
                </label>
                <x-spire::textarea id="observations" name="observations" rows="3"
                    placeholder="Observações adicionais...">{{ old('observations', $serviceOrder?->observations) }}</x-spire::textarea>
            </div>
        </div>
    </x-spire::card>

    {{-- Seção: Agendamento --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Agendamento</h3>
        </x-slot:header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="scheduled_visit_date"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Data Agendada
                </label>
                <x-spire::input id="scheduled_visit_date" name="scheduled_visit_date" type="date"
                    :value="old(
                        'scheduled_visit_date',
                        $serviceOrder?->scheduled_visit_date
                            ? \Carbon\Carbon::parse($serviceOrder->scheduled_visit_date)->format('Y-m-d')
                            : '',
                    )" />
            </div>
        </div>
    </x-spire::card>
</div>
