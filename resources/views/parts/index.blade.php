{{--
    Tabela estilo Filament com interatividade vanilla JS
    Não usa Alpine.js - toda lógica está no FilamentTable.ts
--}}

<x-layouts.module title="Peças">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Peças']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie as peças cadastradas no sistema
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('create', App\Models\Part::class)
            <x-spire::button href="{{ route('parts.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Peça
            </x-spire::button>
        @endcan
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    {{-- Table Container --}}
    @php
        $initialState = [
            'search' => request('search', ''),
            'page' => request('page', 1),
            'perPage' => request('per_page', 15),
            'sortField' => request('sort', 'description'),
            'sortDirection' => request('direction', 'asc'),
            'filters' => [
                'status' => request('status', ''),
                'unit' => request('unit', ''),
                'origin' => request('origin', ''),
            ],
            'visibleColumns' => [
                'code' => true,
                'description' => true,
                'unit' => true,
                'price' => true,
                'status' => true,
            ],
        ];
    @endphp
    <div id="parts-table-container" data-url="{{ route('parts.index') }}" data-csrf="{{ csrf_token() }}"
        data-initial-state='@json($initialState)'>

        {{-- Filament-style Table --}}
        <x-ui.table>
            {{-- Table Header with Search, Filters, etc --}}
            <x-slot:header>
                {{-- Status Tabs (como no Filament) --}}
                <x-ui.table.tabs>
                    <x-ui.table.tab :active="!request('status')" :count="$counts['all'] ?? $parts->total()"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: '' }}))">
                        Todas
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'active'" :count="$counts['active'] ?? null" variant="success"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: 'active' }}))">
                        Ativas
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'inactive'" :count="$counts['inactive'] ?? null" variant="danger"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: 'inactive' }}))">
                        Inativas
                    </x-ui.table.tab>
                </x-ui.table.tabs>

                {{-- Toolbar --}}
                <x-ui.table.header :search="true" searchPlaceholder="Buscar por código, descrição, EAN...">
                    {{-- Bulk Actions (aparecem quando há seleção) --}}
                    <x-slot:bulkActions>
                        <x-spire::button size="sm" variant="danger"
                            onclick="if (confirm('Tem certeza que deseja excluir os itens selecionados?')) window.dispatchEvent(new CustomEvent('table-bulk-delete'))">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Excluir selecionados
                        </x-spire::button>
                    </x-slot:bulkActions>

                    {{-- Filters Dropdown --}}
                    <x-slot:filters>
                        <x-ui.table.filters :activeCount="$activeFiltersCount ?? 0">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unidade</label>
                                <x-spire::select name="filter_unit" placeholder="Todas" :options="[
                                    ['value' => 'UN', 'label' => 'UN - Unidade'],
                                    ['value' => 'PC', 'label' => 'PC - Peça'],
                                    ['value' => 'KIT', 'label' => 'KIT - Kit'],
                                    ['value' => 'CJ', 'label' => 'CJ - Conjunto'],
                                ]"
                                    :value="request('unit', '')" />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Origem</label>
                                <x-spire::select name="filter_origin" placeholder="Todas" :options="[
                                    ['value' => '0', 'label' => '0 - Nacional'],
                                    ['value' => '1', 'label' => '1 - Estrangeira (importação direta)'],
                                    ['value' => '2', 'label' => '2 - Estrangeira (mercado interno)'],
                                ]"
                                    :value="request('origin', '')" />
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
                            <x-ui.table.column-toggle name="description" label="Descrição" :checked="true"
                                :disabled="true" />
                            <x-ui.table.column-toggle name="unit" label="Unidade" :checked="true" />
                            <x-ui.table.column-toggle name="price" label="Preço" :checked="true" />
                            <x-ui.table.column-toggle name="status" label="Status" :checked="true" />
                        </x-ui.table.column-manager>
                    </x-slot:columnManager>
                </x-ui.table.header>
            </x-slot:header>

            {{-- Table Content (updated via AJAX) --}}
            <div class="fi-ta-content">
                @include('parts.partials.table-filament', ['parts' => $parts])
            </div>
        </x-ui.table>
    </div>

    {{-- Initialize FilamentTable Component --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('parts-table-container');
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
                        'filter_unit': 'unit',
                        'filter_origin': 'origin'
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
                            // Update URL without reload
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

                    // Handle bulk delete
                    window.addEventListener('table-bulk-delete', () => {
                        const selected = table.getSelected();
                        if (selected.length === 0) {
                            alert('Nenhum item selecionado');
                            return;
                        }
                        // Implement bulk delete logic here
                        console.log('Delete items:', selected);
                    });
                }
            });
        </script>
    @endpush
</x-layouts.module>
