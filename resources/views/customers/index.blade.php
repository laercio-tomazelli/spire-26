{{--
    Listagem de Clientes estilo Filament
    Não usa Alpine.js - toda lógica está no FilamentTable.ts
--}}

<x-layouts.module title="Clientes">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Clientes']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie os clientes do sistema
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('customers.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Cliente
        </x-spire::button>
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
            'perPage' => request('per_page', 10),
            'sortField' => request('sort', ''),
            'sortDirection' => request('direction', 'asc'),
            'filters' => [
                'customer_type' => request('customer_type', ''),
                'state' => request('state', ''),
                'city' => request('city', ''),
                'type' => request('type', ''),
            ],
            'visibleColumns' => [
                'customer' => true,
                'document' => true,
                'contact' => true,
                'location' => true,
            ],
        ];
    @endphp
    <div id="customers-table-container" data-url="{{ route('customers.index') }}" data-csrf="{{ csrf_token() }}"
        data-initial-state='@json($initialState)'>

        {{-- Filament-style Table --}}
        <x-ui.table>
            {{-- Table Header with Search, Filters, etc --}}
            <x-slot:header>
                {{-- Status Tabs (por tipo de cliente) --}}
                <x-ui.table.tabs>
                    <x-ui.table.tab :active="!request('type')" :count="$counts['all'] ?? $customers->total()" data-tab-filter="type" data-tab-value=""
                        onclick="handleTabClick(this, 'type', '')">
                        Todos
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('type') === 'pf'" :count="$counts['pf'] ?? null" variant="info" data-tab-filter="type"
                        data-tab-value="pf" onclick="handleTabClick(this, 'type', 'pf')">
                        Pessoa Física
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('type') === 'pj'" :count="$counts['pj'] ?? null" variant="info" data-tab-filter="type"
                        data-tab-value="pj" onclick="handleTabClick(this, 'type', 'pj')">
                        Pessoa Jurídica
                    </x-ui.table.tab>
                </x-ui.table.tabs>

                {{-- Toolbar --}}
                <x-ui.table.header :search="true" searchPlaceholder="Buscar clientes...">
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
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                                <x-spire::select name="filter_state" placeholder="Todos" :options="collect($states)
                                    ->map(fn($label, $value) => ['value' => $value, 'label' => $label])
                                    ->values()
                                    ->toArray()"
                                    :value="request('state', '')" />
                            </div>

                            <div class="mt-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cidade</label>
                                <x-spire::input type="text" name="filter_city" placeholder="Digite a cidade"
                                    :value="request('city', '')" />
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
                            <x-ui.table.column-toggle name="customer" label="Cliente" :checked="true"
                                :disabled="true" />
                            <x-ui.table.column-toggle name="document" label="CPF/CNPJ" :checked="true" />
                            <x-ui.table.column-toggle name="contact" label="Contato" :checked="true" />
                            <x-ui.table.column-toggle name="location" label="Localização" :checked="true" />
                        </x-ui.table.column-manager>
                    </x-slot:columnManager>
                </x-ui.table.header>
            </x-slot:header>

            {{-- Table Body --}}
            @include('customers.partials.table', ['customers' => $customers])
        </x-ui.table>
    </div>

    {{-- Initialize FilamentTable Component --}}
    @push('scripts')
        <script>
            // Handle tab click - update visual state and dispatch event
            function handleTabClick(clickedTab, key, value) {
                // Get all tabs in the same group
                const tabs = clickedTab.closest('.fi-ta-tabs').querySelectorAll('[data-tab-filter="' + key + '"]');

                // Update visual state for all tabs
                tabs.forEach(tab => {
                    const isActive = tab === clickedTab;
                    const indicator = tab.querySelector('.absolute.inset-x-0.bottom-0');

                    // Update text color
                    if (isActive) {
                        tab.classList.remove('text-gray-500', 'dark:text-gray-400');
                        tab.classList.add('text-blue-600', 'dark:text-blue-400');

                        // Add indicator if not exists
                        if (!indicator) {
                            const newIndicator = document.createElement('span');
                            newIndicator.className = 'absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 dark:bg-blue-400';
                            tab.appendChild(newIndicator);
                        }
                    } else {
                        tab.classList.remove('text-blue-600', 'dark:text-blue-400');
                        tab.classList.add('text-gray-500', 'dark:text-gray-400');

                        // Remove indicator if exists
                        if (indicator) {
                            indicator.remove();
                        }
                    }
                });

                // Dispatch filter change event
                window.dispatchEvent(new CustomEvent('table-filter-change', {
                    detail: {
                        key,
                        value
                    }
                }));
            }

            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('customers-table-container');
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
                        'filter_state': 'state',
                        'filter_city': 'city'
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
                            Spire.toast.warning('Nenhum item selecionado');
                            return;
                        }

                        // TODO: Implement bulk delete endpoint
                        console.log('Bulk delete IDs:', selected);
                        Spire.toast.info('Funcionalidade de exclusão em massa será implementada em breve.');
                    });
                }
            });
        </script>
    @endpush
</x-layouts.module>
