<x-layouts.module title="Nova Movimentação de Estoque">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Estoque'],
            ['label' => 'Movimentações', 'href' => route('inventory-transactions.index')],
            ['label' => 'Nova'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Registrar movimentação manual de estoque
    </x-slot:header>

    <x-spire::card>
        <form id="transaction-form" action="{{ route('inventory-transactions.store') }}" method="POST">
            @csrf

            {{-- Transaction Type Selection --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Tipo de Movimentação <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="transaction-types-grid">
                    @foreach ($transactionTypes as $type)
                        @php
                            $isSelected = old('transaction_type_id') == $type->id;
                            $baseClasses =
                                'transaction-type-card relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200';
                            $selectedClasses = match ($type->operation) {
                                'in' => 'border-green-500 bg-green-50 dark:bg-green-900/20',
                                'out' => 'border-red-500 bg-red-50 dark:bg-red-900/20',
                                'transfer' => 'border-blue-500 bg-blue-50 dark:bg-blue-900/20',
                                default => 'border-gray-300',
                            };
                            $unselectedClasses =
                                'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600';
                        @endphp
                        <label class="{{ $baseClasses }} {{ $isSelected ? $selectedClasses : $unselectedClasses }}"
                            data-type-id="{{ $type->id }}" data-operation="{{ $type->operation }}"
                            data-description="{{ $type->description }}">
                            <input type="radio" name="transaction_type_id" value="{{ $type->id }}"
                                class="sr-only transaction-type-radio" {{ $isSelected ? 'checked' : '' }}>
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ str_replace('_', ' ', $type->type) }}
                                </div>
                                <div
                                    class="text-xs mt-1 {{ match ($type->operation) {
                                        'in' => 'text-green-600 dark:text-green-400',
                                        'out' => 'text-red-600 dark:text-red-400',
                                        'transfer' => 'text-blue-600 dark:text-blue-400',
                                        default => 'text-gray-500',
                                    } }}">
                                    @if ($type->operation === 'in')
                                        ↓ Entrada
                                    @elseif ($type->operation === 'out')
                                        ↑ Saída
                                    @else
                                        ↔ Transferência
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('transaction_type_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Operation indicator --}}
            <div id="operation-indicator" class="mb-6 p-4 rounded-lg hidden">
                <div class="flex items-center gap-3">
                    <div class="text-2xl" id="operation-icon"></div>
                    <div>
                        <div class="font-medium" id="operation-label"></div>
                        <div class="text-sm" id="operation-description"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Depósito --}}
                <x-spire::select name="warehouse_id" label="Depósito" :value="old('warehouse_id')" required
                    placeholder="Selecione o depósito" :options="$warehouses
                        ->map(fn($w) => ['value' => $w->id, 'label' => $w->code . ' - ' . $w->name])
                        ->toArray()" :error="$errors->first('warehouse_id')" />

                {{-- Peça --}}
                <x-spire::select name="part_id" label="Peça" :value="old('part_id')" required placeholder="Selecione a peça"
                    :options="$parts
                        ->map(
                            fn($p) => [
                                'value' => $p->id,
                                'label' => $p->part_code . ' - ' . Str::limit($p->description, 40),
                            ],
                        )
                        ->toArray()" :error="$errors->first('part_id')" />

                {{-- Quantidade --}}
                <x-spire::input type="number" name="quantity" label="Quantidade" :value="old('quantity', 1)" required
                    min="1" step="1" placeholder="Quantidade a movimentar" :error="$errors->first('quantity')" />

                {{-- Tipo de Documento --}}
                <x-spire::select name="document_type_id" label="Tipo de Documento" :value="old('document_type_id')" required
                    placeholder="Selecione o tipo" :options="$documentTypes
                        ->map(fn($d) => ['value' => $d->id, 'label' => $d->type . ' - ' . $d->description])
                        ->toArray()" :error="$errors->first('document_type_id')" />

                {{-- Número do Documento --}}
                <x-spire::input name="document_number" label="Número do Documento" :value="old('document_number')" maxlength="50"
                    placeholder="Ex: NF-12345" :error="$errors->first('document_number')" />

                {{-- Preço Unitário --}}
                <x-spire::input type="number" name="unit_price" label="Preço Unitário" :value="old('unit_price', '0.00')"
                    min="0" step="0.01" placeholder="0,00" :error="$errors->first('unit_price')" />

                {{-- Custo Unitário --}}
                <x-spire::input type="number" name="cost_price" label="Custo Unitário" :value="old('cost_price', '0.00')"
                    min="0" step="0.01" placeholder="0,00" :error="$errors->first('cost_price')" />
            </div>

            {{-- Observações --}}
            <div class="mt-6">
                <x-spire::textarea name="observations" label="Observações" :value="old('observations')" rows="3"
                    placeholder="Observações sobre esta movimentação..." :error="$errors->first('observations')" />
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <x-spire::button variant="outline" href="{{ route('inventory-transactions.index') }}">
                    Cancelar
                </x-spire::button>
                <x-spire::button type="submit">
                    Registrar Movimentação
                </x-spire::button>
            </div>
        </form>
    </x-spire::card>

    {{-- Vanilla JS for transaction type selection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.transaction-type-card');
            const indicator = document.getElementById('operation-indicator');
            const operationIcon = document.getElementById('operation-icon');
            const operationLabel = document.getElementById('operation-label');
            const operationDescription = document.getElementById('operation-description');

            const operationConfig = {
                'in': {
                    icon: '📥',
                    label: 'Entrada',
                    bgClass: 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800',
                    labelClass: 'text-green-700 dark:text-green-300',
                    descClass: 'text-green-600 dark:text-green-400',
                    selectedClass: 'border-green-500 bg-green-50 dark:bg-green-900/20'
                },
                'out': {
                    icon: '📤',
                    label: 'Saída',
                    bgClass: 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800',
                    labelClass: 'text-red-700 dark:text-red-300',
                    descClass: 'text-red-600 dark:text-red-400',
                    selectedClass: 'border-red-500 bg-red-50 dark:bg-red-900/20'
                },
                'transfer': {
                    icon: '🔄',
                    label: 'Transferência',
                    bgClass: 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800',
                    labelClass: 'text-blue-700 dark:text-blue-300',
                    descClass: 'text-blue-600 dark:text-blue-400',
                    selectedClass: 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                }
            };

            const unselectedClass =
                'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600';

            function updateSelection(selectedCard) {
                const operation = selectedCard.dataset.operation;
                const description = selectedCard.dataset.description;
                const config = operationConfig[operation];

                // Update all cards
                cards.forEach(card => {
                    const cardOperation = card.dataset.operation;
                    const cardConfig = operationConfig[cardOperation];

                    // Remove all selection classes
                    card.classList.remove(
                        'border-green-500', 'bg-green-50', 'dark:bg-green-900/20',
                        'border-red-500', 'bg-red-50', 'dark:bg-red-900/20',
                        'border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20',
                        'border-gray-200', 'dark:border-gray-700', 'hover:border-gray-300',
                        'dark:hover:border-gray-600'
                    );

                    if (card === selectedCard) {
                        // Add selected classes
                        cardConfig.selectedClass.split(' ').forEach(cls => card.classList.add(cls));
                    } else {
                        // Add unselected classes
                        unselectedClass.split(' ').forEach(cls => card.classList.add(cls));
                    }
                });

                // Update indicator
                indicator.className = 'mb-6 p-4 rounded-lg ' + config.bgClass;
                indicator.classList.remove('hidden');
                operationIcon.textContent = config.icon;
                operationLabel.className = 'font-medium ' + config.labelClass;
                operationLabel.textContent = 'Operação: ' + config.label;
                operationDescription.className = 'text-sm ' + config.descClass;
                operationDescription.textContent = description;
            }

            // Handle card clicks
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const radio = this.querySelector('.transaction-type-radio');
                    radio.checked = true;
                    updateSelection(this);
                });
            });

            // Initialize with selected card if any
            const checkedRadio = document.querySelector('.transaction-type-radio:checked');
            if (checkedRadio) {
                const selectedCard = checkedRadio.closest('.transaction-type-card');
                updateSelection(selectedCard);
            }
        });
    </script>
</x-layouts.module>
