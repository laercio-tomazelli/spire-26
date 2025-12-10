{{-- Tabela estilo Filament --}}
<x-layouts.module title="Linhas de Produto">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Linhas de Produto']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie as linhas de produto (Linha Branca, Marrom, Informática, etc.)
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-lines.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova Linha
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
            'filters' => [],
            'visibleColumns' => [
                'name' => true,
                'categories' => true,
            ],
        ];
    @endphp
    <div id="product-lines-table-container" data-url="{{ route('product-lines.index') }}"
        data-csrf="{{ csrf_token() }}" data-initial-state='@json($initialState)'>

        <x-ui.table>
            <x-slot:header>
                <x-ui.table.header :search="true" searchPlaceholder="Buscar linhas...">
                </x-ui.table.header>
            </x-slot:header>

            <div class="fi-ta-content">
                @include('product-lines.partials.table-filament', ['productLines' => $productLines])
            </div>
        </x-ui.table>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('product-lines-table-container');
                if (!container || typeof Spire === 'undefined' || !Spire.FilamentTable) return;

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
