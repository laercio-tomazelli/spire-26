{{--
    Listagem de Ordens de Serviço estilo Filament
    Não usa Alpine.js - toda lógica está no FilamentTable.ts
--}}

<x-layouts.module title="Ordens de Serviço">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Ordens de Serviço']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie as ordens de serviço
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('service-orders.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova OS
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
            'perPage' => request('per_page', 15),
            'sortField' => request('sort', 'order_number'),
            'sortDirection' => request('direction', 'desc'),
            'filters' => [
                'status' => request('status', ''),
                'partner_id' => request('partner_id', ''),
                'brand_id' => request('brand_id', ''),
                'warranty_type' => request('warranty_type', ''),
                'date_from' => request('date_from', ''),
                'date_to' => request('date_to', ''),
            ],
            'visibleColumns' => [
                'order' => true,
                'customer' => true,
                'product' => true,
                'status' => true,
                'partner' => true,
                'dates' => true,
            ],
        ];
    @endphp
    <div id="service-orders-table-container" data-url="{{ route('service-orders.index') }}"
        data-csrf="{{ csrf_token() }}" data-initial-state='@json($initialState)'>

        {{-- Filament-style Table --}}
        <x-ui.table>
            {{-- Table Header with Search, Filters, etc --}}
            <x-slot:header>
                {{-- Status Tabs --}}
                <x-ui.table.tabs>
                    <x-ui.table.tab :active="!request('status')" :count="$statuses['all'] ?? 0" data-tab-filter="status" data-tab-value=""
                        onclick="handleTabClick(this, 'status', '')">
                        Todas
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'open'" :count="$statuses['open'] ?? 0" variant="success" data-tab-filter="status"
                        data-tab-value="open" onclick="handleTabClick(this, 'status', 'open')">
                        Abertas
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'closed'" :count="$statuses['closed'] ?? 0" variant="info" data-tab-filter="status"
                        data-tab-value="closed" onclick="handleTabClick(this, 'status', 'closed')">
                        Finalizadas
                    </x-ui.table.tab>
                    <x-ui.table.tab :active="request('status') === 'canceled'" :count="$statuses['canceled'] ?? 0" variant="danger" data-tab-filter="status"
                        data-tab-value="canceled" onclick="handleTabClick(this, 'status', 'canceled')">
                        Canceladas
                    </x-ui.table.tab>
                </x-ui.table.tabs>

                {{-- Toolbar --}}
                <x-ui.table.header :search="true"
                    searchPlaceholder="Buscar por número, protocolo, cliente, série...">
                    {{-- Bulk Actions --}}
                    <x-slot:bulkActions>
                        <x-spire::button size="sm" variant="secondary"
                            onclick="window.dispatchEvent(new CustomEvent('table-export-selected'))">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Exportar
                        </x-spire::button>
                    </x-slot:bulkActions>

                    {{-- Filters Dropdown --}}
                    <x-slot:filters>
                        <x-ui.table.filters :activeCount="$activeFiltersCount ?? 0">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Posto</label>
                                <x-spire::select name="filter_partner_id" placeholder="Todos" :options="collect($partners)
                                    ->map(fn($label, $value) => ['value' => $value, 'label' => $label])
                                    ->values()
                                    ->toArray()"
                                    :value="request('partner_id', '')" />
                            </div>

                            <div class="mt-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marca</label>
                                <x-spire::select name="filter_brand_id" placeholder="Todas" :options="collect($brands)
                                    ->map(fn($label, $value) => ['value' => $value, 'label' => $label])
                                    ->values()
                                    ->toArray()"
                                    :value="request('brand_id', '')" />
                            </div>

                            <div class="mt-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Garantia</label>
                                <x-spire::select name="filter_warranty_type" placeholder="Todas" :options="[
                                    ['value' => 'in_warranty', 'label' => 'Em Garantia'],
                                    ['value' => 'out_of_warranty', 'label' => 'Fora de Garantia'],
                                ]"
                                    :value="request('warranty_type', '')" />
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">De</label>
                                    <x-spire::input type="date" name="filter_date_from" :value="request('date_from', '')" />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Até</label>
                                    <x-spire::input type="date" name="filter_date_to" :value="request('date_to', '')" />
                                </div>
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
                            <x-ui.table.column-toggle name="order" label="OS" :checked="true"
                                :disabled="true" />
                            <x-ui.table.column-toggle name="customer" label="Cliente" :checked="true" />
                            <x-ui.table.column-toggle name="product" label="Produto" :checked="true" />
                            <x-ui.table.column-toggle name="status" label="Status" :checked="true" />
                            <x-ui.table.column-toggle name="partner" label="Posto" :checked="true" />
                            <x-ui.table.column-toggle name="dates" label="Datas" :checked="true" />
                        </x-ui.table.column-manager>
                    </x-slot:columnManager>
                </x-ui.table.header>
            </x-slot:header>

            {{-- Table Body --}}
            @include('service-orders.partials.table', ['serviceOrders' => $serviceOrders])
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
                            newIndicator.className =
                                'absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 dark:bg-blue-400';
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
                const container = document.getElementById('service-orders-table-container');
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
                        'filter_partner_id': 'partner_id',
                        'filter_brand_id': 'brand_id',
                        'filter_warranty_type': 'warranty_type'
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

                    // Handle export selected
                    window.addEventListener('table-export-selected', () => {
                        const selected = table.getSelected();
                        if (selected.length === 0) {
                            Spire.toast.warning('Nenhum item selecionado');
                            return;
                        }

                        // TODO: Implement export endpoint
                        console.log('Export IDs:', selected);
                        Spire.toast.info('Funcionalidade de exportação será implementada em breve.');
                    });
                }
            });
        </script>
    @endpush
</x-layouts.module>
