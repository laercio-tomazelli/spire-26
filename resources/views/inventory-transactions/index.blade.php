{{--
    Tabela estilo Filament - Movimentações de Estoque
--}}

<x-layouts.module title="Movimentações">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Estoque'],
            ['label' => 'Movimentações'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Histórico de movimentações de estoque
    </x-slot:header>

    {{-- Table Container --}}
    @php
        $initialState = [
            'search' => request('search', ''),
            'page' => request('page', 1),
            'perPage' => request('per_page', 15),
            'sortField' => request('sort', 'created_at'),
            'sortDirection' => request('direction', 'desc'),
            'filters' => [
                'type' => request('type', ''),
                'warehouse_id' => request('warehouse_id', ''),
                'date_from' => request('date_from', ''),
                'date_to' => request('date_to', ''),
            ],
            'visibleColumns' => [
                'date' => true,
                'part' => true,
                'warehouse' => true,
                'quantity' => true,
                'document' => true,
                'user' => true,
            ],
        ];
    @endphp
    <div id="transactions-table-container" data-url="{{ route('inventory-transactions.index') }}"
        data-csrf="{{ csrf_token() }}" data-initial-state='@json($initialState)'>

        {{-- Filament-style Table --}}
        <x-ui.table>
            {{-- Table Header with Search, Filters, etc --}}
            <x-slot:header>
                {{-- Type Tabs --}}
                <x-ui.table.tabs>
                    <x-ui.table.tab :active="!request('type')" :count="$counts['all'] ?? $transactions->total()"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'type', value: '' }}))">
                        Todas
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('type') === 'in'" :count="$counts['in'] ?? null" variant="success"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'type', value: 'in' }}))">
                        Entradas
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('type') === 'out'" :count="$counts['out'] ?? null" variant="danger"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'type', value: 'out' }}))">
                        Saídas
                    </x-ui.table.tab>
                </x-ui.table.tabs>

                {{-- Toolbar --}}
                <x-ui.table.header :search="true" searchPlaceholder="Buscar por código, descrição ou documento...">
                    {{-- Filters Dropdown --}}
                    <x-slot:filters>
                        <x-ui.table.filters :activeCount="$activeFiltersCount ?? 0">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Depósito</label>
                                <x-spire::select name="filter_warehouse_id" placeholder="Todos" :options="$warehouses
                                    ->map(fn($w) => ['value' => $w->id, 'label' => $w->code . ' - ' . $w->name])
                                    ->toArray()"
                                    :value="request('warehouse_id', '')" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data
                                    Inicial</label>
                                <x-spire::input type="date" name="filter_date_from" :value="request('date_from', '')" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data
                                    Final</label>
                                <x-spire::input type="date" name="filter_date_to" :value="request('date_to', '')" />
                            </div>

                            <x-slot:footer>
                                <x-spire::button class="w-full"
                                    onclick="window.dispatchEvent(new CustomEvent('table-apply-filters'))">
                                    Aplicar filtros
                                </x-spire::button>
                            </x-slot:footer>
                        </x-ui.table.filters>
                    </x-slot:filters>

                    {{-- Column Manager --}}
                    <x-slot:columnManager>
                        <x-ui.table.column-manager>
                            <x-ui.table.column-toggle name="date" label="Data" :checked="true"
                                :disabled="true" />
                            <x-ui.table.column-toggle name="part" label="Peça" :checked="true" />
                            <x-ui.table.column-toggle name="warehouse" label="Depósito" :checked="true" />
                            <x-ui.table.column-toggle name="quantity" label="Quantidade" :checked="true" />
                            <x-ui.table.column-toggle name="document" label="Documento" :checked="true" />
                            <x-ui.table.column-toggle name="user" label="Usuário" :checked="true" />
                        </x-ui.table.column-manager>
                    </x-slot:columnManager>
                </x-ui.table.header>
            </x-slot:header>

            {{-- Table Content (updated via AJAX) --}}
            <div class="fi-ta-content">
                @include('inventory-transactions.partials.table-filament', [
                    'transactions' => $transactions,
                ])
            </div>
        </x-ui.table>
    </div>

    {{-- Initialize FilamentTable Component --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('transactions-table-container');
                if (!container) return;

                const url = container.dataset.url;
                const csrfToken = container.dataset.csrf;
                const initialState = JSON.parse(container.dataset.initialState || '{}');

                // Listen for select changes from spire::select components
                window.addEventListener('select-change', (e) => {
                    const {
                        name,
                        value
                    } = e.detail;
                    const keyMap = {
                        'filter_warehouse_id': 'warehouse_id'
                    };
                    const key = keyMap[name];
                    if (key) {
                        window.dispatchEvent(new CustomEvent('table-filter-change', {
                            detail: {
                                key,
                                value
                            }
                        }));
                    }
                });

                // Create FilamentTable instance
                if (typeof Spire !== 'undefined' && Spire.FilamentTable) {
                    const table = new Spire.FilamentTable({
                        url: url,
                        container: container,
                        contentSelector: '.fi-ta-content',
                        csrfToken: csrfToken,
                        initialState: initialState,
                        onUpdate: (state) => {
                            const params = new URLSearchParams();
                            if (state.search) params.append('search', state.search);
                            if (state.sortField) {
                                params.append('sort', state.sortField);
                                params.append('direction', state.sortDirection);
                            }
                            params.append('page', state.page);
                            params.append('per_page', state.perPage);
                            Object.entries(state.filters).forEach(([key, value]) => {
                                if (value) params.append(key, value);
                            });
                            const newUrl = url + (params.toString() ? '?' + params.toString() : '');
                            window.history.replaceState({}, '', newUrl);
                        }
                    });
                }
            });
        </script>
    @endpush
</x-layouts.module>
