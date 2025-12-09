{{--
    Tabela estilo Filament para Manufacturers
--}}

<x-layouts.module title="Fabricantes">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Fabricantes']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie os fabricantes do sistema
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('manufacturers.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Fabricante
        </x-spire::button>
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    @if (session('error'))
        <x-spire::alert type="error" class="mb-6">
            {{ session('error') }}
        </x-spire::alert>
    @endif

    {{-- Table Container --}}
    @php
        $initialState = [
            'search' => request('search', ''),
            'page' => request('page', 1),
            'perPage' => request('per_page', 10),
            'sortField' => request('sort', ''),
            'sortDirection' => request('direction', 'asc'),
            'filters' => [
                'tenant_id' => request('tenant_id', ''),
                'is_active' => request('is_active', ''),
                'status' => request('status', ''),
            ],
            'visibleColumns' => [
                'manufacturer' => true,
                'tenant' => true,
                'contact' => true,
                'stats' => true,
                'status' => true,
            ],
        ];
    @endphp
    <div id="manufacturers-table-container" data-url="{{ route('manufacturers.index') }}"
        data-csrf="{{ csrf_token() }}" data-initial-state='@json($initialState)'>

        {{-- Filament-style Table --}}
        <x-ui.table>
            {{-- Table Header with Search, Filters, etc --}}
            <x-slot:header>
                {{-- Status Tabs --}}
                <x-ui.table.tabs>
                    <x-ui.table.tab :active="!request('status')" :count="$counts['all'] ?? $manufacturers->total()"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: '' }}))">
                        Todos
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'active'" :count="$counts['active'] ?? null" variant="success"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: 'active' }}))">
                        Ativos
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'inactive'" :count="$counts['inactive'] ?? null" variant="danger"
                        onclick="window.dispatchEvent(new CustomEvent('table-filter-change', { detail: { key: 'status', value: 'inactive' }}))">
                        Inativos
                    </x-ui.table.tab>
                </x-ui.table.tabs>

                {{-- Toolbar --}}
                <x-ui.table.header :search="true" searchPlaceholder="Buscar fabricantes...">
                    {{-- Bulk Actions --}}
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
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tenant</label>
                                <x-spire::select name="filter_tenant_id" placeholder="Todos" :options="$tenants
                                    ->map(fn($t) => ['value' => (string) $t->id, 'label' => $t->name])
                                    ->toArray()"
                                    :value="request('tenant_id', '')" />
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
                            <x-ui.table.column-toggle name="manufacturer" label="Fabricante" :checked="true"
                                :disabled="true" />
                            <x-ui.table.column-toggle name="tenant" label="Tenant" :checked="true" />
                            <x-ui.table.column-toggle name="contact" label="Contato" :checked="true" />
                            <x-ui.table.column-toggle name="stats" label="Estatísticas" :checked="true" />
                            <x-ui.table.column-toggle name="status" label="Status" :checked="true" />
                        </x-ui.table.column-manager>
                    </x-slot:columnManager>
                </x-ui.table.header>
            </x-slot:header>

            {{-- Table Content --}}
            <div class="fi-ta-content">
                @include('manufacturers.partials.table', ['manufacturers' => $manufacturers])
            </div>
        </x-ui.table>
    </div>

    {{-- Initialize FilamentTable Component --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('manufacturers-table-container');
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
                        'filter_tenant_id': 'tenant_id'
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
