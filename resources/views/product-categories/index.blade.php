{{-- Tabela estilo Filament --}}
<x-layouts.module title="Categorias de Produto">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Categorias de Produto']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie as categorias de produto (TV, Geladeira, Notebook, etc.)
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-categories.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova Categoria
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
                'product_line_id' => request('product_line_id', ''),
            ],
            'visibleColumns' => [
                'name' => true,
                'product_line' => true,
                'models' => true,
            ],
        ];
    @endphp
    <div id="product-categories-table-container" data-url="{{ route('product-categories.index') }}"
        data-csrf="{{ csrf_token() }}" data-initial-state='@json($initialState)'>

        <x-ui.table>
            <x-slot:header>
                <x-ui.table.header :search="true" searchPlaceholder="Buscar categorias...">
                    {{-- Filters Dropdown --}}
                    <x-slot:filters>
                        <x-ui.table.filters :activeCount="$activeFiltersCount ?? 0">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Linha de Produto
                                </label>
                                <x-spire::select name="filter_product_line_id" placeholder="Todas" :options="$productLines
                                    ->map(fn($pl) => ['value' => (string) $pl->id, 'label' => $pl->name])
                                    ->values()
                                    ->toArray()"
                                    :value="request('product_line_id', '')" />
                            </div>

                            <x-slot:footer>
                                <x-spire::button class="w-full"
                                    onclick="window.dispatchEvent(new CustomEvent('table-apply-filters'))">
                                    Aplicar filtros
                                </x-spire::button>
                            </x-slot:footer>
                        </x-ui.table.filters>
                    </x-slot:filters>
                </x-ui.table.header>
            </x-slot:header>

            <div class="fi-ta-content">
                @include('product-categories.partials.table-filament', ['categories' => $categories])
            </div>
        </x-ui.table>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('product-categories-table-container');
                if (!container || typeof Spire === 'undefined' || !Spire.FilamentTable) return;

                window.addEventListener('select-change', (e) => {
                    const {
                        name,
                        value
                    } = e.detail;
                    if (name === 'filter_product_line_id') {
                        window.dispatchEvent(new CustomEvent('table-filter-change', {
                            detail: {
                                key: 'product_line_id',
                                value
                            }
                        }));
                    }
                });

                const table = new Spire.FilamentTable({
                    url: container.dataset.url,
                    container: container,
                    contentSelector: '.fi-ta-content',
                    csrfToken: container.dataset.csrf,
                    initialState: JSON.parse(container.dataset.initialState || '{}'),
                });
            });
        </script>
    @endpush
</x-layouts.module>
