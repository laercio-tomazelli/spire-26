<x-layouts.module title="Times">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Times']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie os times e suas permissões
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('create', App\Models\Team::class)
            <x-spire::button href="{{ route('teams.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Time
            </x-spire::button>
        @endcan
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    {{-- Tabs --}}
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex gap-4" aria-label="Tabs">
            <button type="button" data-tab="all"
                class="filament-tab group inline-flex items-center gap-2 px-1 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ !request('status') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                {{ !request('status') ? 'aria-current=page' : '' }}>
                Todos
                <span
                    class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ !request('status') ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                    {{ $counts['all'] }}
                </span>
            </button>
            <button type="button" data-tab="active"
                class="filament-tab group inline-flex items-center gap-2 px-1 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'active' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Ativos
                <span
                    class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'active' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                    {{ $counts['active'] }}
                </span>
            </button>
            <button type="button" data-tab="inactive"
                class="filament-tab group inline-flex items-center gap-2 px-1 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'inactive' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                Inativos
                <span
                    class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'inactive' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                    {{ $counts['inactive'] }}
                </span>
            </button>
        </nav>
    </div>

    {{-- Search and Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        {{-- Search --}}
        <div class="relative flex-1">
            <input type="text" id="search-input" placeholder="Buscar times..."
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
                        data-column="team" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Time</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="users" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Usuários</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="roles" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Perfis</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="status" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Status</span>
                </label>
            </div>
        </x-spire::dropdown>
    </div>

    {{-- Table Container --}}
    <div id="table-container">
        @include('teams.partials.table', ['teams' => $teams])
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
                    baseUrl: '{{ route('teams.index') }}',
                    tabParamName: 'status',
                });
            });
        </script>
    @endpush
</x-layouts.module>
