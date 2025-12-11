{{--
    Tabela estilo Filament com interatividade vanilla JS
    Não usa Alpine.js - toda lógica está no FilamentTable.ts
--}}

<x-layouts.module title="Depósitos">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Estoque'],
            ['label' => 'Depósitos'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie os depósitos de estoque
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('create', App\Models\Warehouse::class)
            <x-spire::button href="{{ route('warehouses.create') }}">
                <x-spire::icon name="plus" size="sm" class="mr-2" />
                Novo Depósito
            </x-spire::button>
        @endcan
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    @if (session('error'))
        <x-spire::alert type="danger" class="mb-6">
            {{ session('error') }}
        </x-spire::alert>
    @endif

    {{-- Table Container --}}
    @php
        $initialState = [
            'search' => request('search', ''),
            'page' => request('page', 1),
            'perPage' => request('per_page', 15),
            'sortField' => request('sort', 'name'),
            'sortDirection' => request('direction', 'asc'),
            'filters' => [
                'type' => request('type', ''),
                'partner_id' => request('partner_id', ''),
            ],
            'visibleColumns' => [
                'code' => true,
                'name' => true,
                'type' => true,
                'location' => true,
                'partner' => true,
                'items' => true,
            ],
        ];
    @endphp
    <div id="warehouses-table-container" data-url="{{ route('warehouses.index') }}" data-csrf="{{ csrf_token() }}"
        data-initial-state='@json($initialState)'>

        {{-- Filament-style Table --}}
        <x-ui.table>
            {{-- Table Header with Search, Filters, etc --}}
            <x-slot:header>
                {{-- Type Tabs --}}
                <x-ui.table.tabs>
                    <x-ui.table.tab :active="!request('type')" :count="$counts['all'] ?? $warehouses->total()"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'type', value: '' }}))">
                        Todos
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('type') === 'main'" :count="$counts['main'] ?? null" variant="primary"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'type', value: 'main' }}))">
                        Principal
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('type') === 'partner'" :count="$counts['partner'] ?? null" variant="info"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'type', value: 'partner' }}))">
                        Parceiro
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('type') === 'buffer'" :count="$counts['buffer'] ?? null" variant="warning"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'type', value: 'buffer' }}))">
                        Buffer
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('type') === 'defective'" :count="$counts['defective'] ?? null" variant="danger"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'type', value: 'defective' }}))">
                        Defeituosos
                    </x-ui.table.tab>
                </x-ui.table.tabs>

                {{-- Toolbar --}}
                <x-ui.table.header :search="true" searchPlaceholder="Buscar por nome, código ou localização...">
                    {{-- Bulk Actions --}}
                    <x-slot:bulkActions>
                        <x-spire::button size="sm" variant="danger"
                            onclick="if (confirm('Tem certeza que deseja excluir os itens selecionados?')) window.dispatchEvent(new CustomEvent('table-bulk-delete'))">
                            <x-spire::icon name="trash" size="sm" class="mr-1" />
                            Excluir selecionados
                        </x-spire::button>
                    </x-slot:bulkActions>

                    {{-- Filters Dropdown --}}
                    <x-slot:filters>
                        <x-ui.table.filters :activeCount="$activeFiltersCount ?? 0">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parceiro</label>
                                <x-spire::select name="filter_partner_id" placeholder="Todos" :options="$partners
                                    ->map(fn($p) => ['value' => $p->id, 'label' => $p->trade_name])
                                    ->toArray()"
                                    :value="request('partner_id', '')" />
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
                            <x-ui.table.column-toggle name="code" label="Código" :checked="true" />
                            <x-ui.table.column-toggle name="name" label="Nome" :checked="true"
                                :disabled="true" />
                            <x-ui.table.column-toggle name="type" label="Tipo" :checked="true" />
                            <x-ui.table.column-toggle name="location" label="Localização" :checked="true" />
                            <x-ui.table.column-toggle name="partner" label="Parceiro" :checked="true" />
                            <x-ui.table.column-toggle name="items" label="Itens" :checked="true" />
                        </x-ui.table.column-manager>
                    </x-slot:columnManager>
                </x-ui.table.header>
            </x-slot:header>

            {{-- Table Content (updated via AJAX) --}}
            <div class="fi-ta-content">
                @include('warehouses.partials.table-filament', ['warehouses' => $warehouses])
            </div>
        </x-ui.table>
    </div>

    {{-- Initialize FilamentTable Component --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('warehouses-table-container');
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
                        'filter_partner_id': 'partner_id'
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
