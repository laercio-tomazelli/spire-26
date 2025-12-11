{{--
    Tabela estilo Filament - Inventário
--}}

<x-layouts.module title="Estoque">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Estoque'],
            ['label' => 'Itens'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Visualize o estoque de peças nos depósitos
    </x-slot:header>

    {{-- Table Container --}}
    @php
        $initialState = [
            'search' => request('search', ''),
            'page' => request('page', 1),
            'perPage' => request('per_page', 15),
            'sortField' => request('sort', 'part_code'),
            'sortDirection' => request('direction', 'asc'),
            'filters' => [
                'status' => request('status', ''),
                'warehouse_id' => request('warehouse_id', ''),
                'part_id' => request('part_id', ''),
            ],
            'visibleColumns' => [
                'part_code' => true,
                'part' => true,
                'warehouse' => true,
                'available' => true,
                'reserved' => true,
                'defective' => true,
            ],
        ];
    @endphp
    <div id="inventory-table-container" data-url="{{ route('inventory.index') }}" data-csrf="{{ csrf_token() }}"
        data-initial-state='@json($initialState)'>

        {{-- Filament-style Table --}}
        <x-ui.table>
            {{-- Table Header with Search, Filters, etc --}}
            <x-slot:header>
                {{-- Status Tabs --}}
                <x-ui.table.tabs>
                    <x-ui.table.tab :active="!request('status')" :count="$counts['all'] ?? $items->total()"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: '' }}))">
                        Todos
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'available'" :count="$counts['available'] ?? null" variant="success"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: 'available' }}))">
                        Disponível
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'reserved'" :count="$counts['reserved'] ?? null" variant="warning"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: 'reserved' }}))">
                        Reservado
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'defective'" :count="$counts['defective'] ?? null" variant="danger"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: 'defective' }}))">
                        Defeituoso
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'empty'" :count="$counts['empty'] ?? null" variant="secondary"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: 'empty' }}))">
                        Zerado
                    </x-ui.table.tab>
                </x-ui.table.tabs>

                {{-- Toolbar --}}
                <x-ui.table.header :search="true" searchPlaceholder="Buscar por código ou descrição da peça...">
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
                            <x-ui.table.column-toggle name="part_code" label="Código" :checked="true"
                                :disabled="true" />
                            <x-ui.table.column-toggle name="part" label="Peça" :checked="true" />
                            <x-ui.table.column-toggle name="warehouse" label="Depósito" :checked="true" />
                            <x-ui.table.column-toggle name="available" label="Disponível" :checked="true" />
                            <x-ui.table.column-toggle name="reserved" label="Reservado" :checked="true" />
                            <x-ui.table.column-toggle name="defective" label="Defeituoso" :checked="true" />
                        </x-ui.table.column-manager>
                    </x-slot:columnManager>
                </x-ui.table.header>
            </x-slot:header>

            {{-- Table Content (updated via AJAX) --}}
            <div class="fi-ta-content">
                @include('inventory.partials.table-filament', ['items' => $items])
            </div>
        </x-ui.table>
    </div>

    {{-- Initialize FilamentTable Component --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('inventory-table-container');
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
