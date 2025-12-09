<x-layouts.module title="Perfis">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Perfis']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie os perfis de acesso do sistema
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('create', App\Models\Role::class)
            <x-spire::button href="{{ route('roles.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Perfil
            </x-spire::button>
        @endcan
    </x-slot:headerActions>

    {{-- Tabs --}}
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex gap-4" aria-label="Tabs">
            <button type="button" data-tab="all"
                class="filament-tab group inline-flex items-center gap-2 px-1 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap border-blue-500 text-blue-600 dark:text-blue-400"
                aria-current="page">
                Todos
                <span
                    class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ $counts['all'] }}
                </span>
            </button>
            <button type="button" data-tab="system"
                class="filament-tab group inline-flex items-center gap-2 px-1 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                Sistema
                <span
                    class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {{ $counts['system'] }}
                </span>
            </button>
            <button type="button" data-tab="custom"
                class="filament-tab group inline-flex items-center gap-2 px-1 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                Personalizados
                <span
                    class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {{ $counts['custom'] }}
                </span>
            </button>
        </nav>
    </div>

    {{-- Search and Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        {{-- Search --}}
        <div class="relative flex-1">
            <input type="text" id="search-input" placeholder="Buscar perfis..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        {{-- Column Visibility Toggle --}}
        <x-spire::dropdown align="right" width="w-56">
            <x-slot:triggerSlot>
                <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                    Colunas
                </button>
            </x-slot:triggerSlot>

            <div class="p-2 space-y-1">
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="role" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Perfil</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="permissions" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Permissões</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="users" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Usuários</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="type" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Tipo</span>
                </label>
            </div>
        </x-spire::dropdown>
    </div>

    {{-- Table Container --}}
    <div id="table-container">
        @include('roles.partials.table', ['roles' => $roles])
    </div>

    @push('scripts')
        <script type="module">
            import {
                FilamentTable
            } from '/resources/js/FilamentTable.ts';

            document.addEventListener('DOMContentLoaded', () => {
                new FilamentTable({
                    tableContainerId: 'table-container',
                    searchInputId: 'search-input',
                    baseUrl: '{{ route('roles.index') }}',
                    tabParamName: 'status',
                });
            });
        </script>
    @endpush
</x-layouts.module>
