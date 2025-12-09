<x-layouts.module title="Permissões">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Permissões']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie as permissões do sistema
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('create', App\Models\Permission::class)
            <x-spire::button href="{{ route('permissions.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Permissão
            </x-spire::button>
        @endcan
    </x-slot:headerActions>

    {{-- Search and Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        {{-- Search --}}
        <div class="relative flex-1">
            <input type="text" id="search-input" placeholder="Buscar permissões..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        {{-- Group Filter --}}
        <select id="group-filter"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
            <option value="">Todos os grupos</option>
            @foreach ($groups as $group)
                <option value="{{ $group }}" {{ request('group') === $group ? 'selected' : '' }}>
                    {{ ucfirst($group) }}
                </option>
            @endforeach
        </select>

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
                        data-column="permission" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Permissão</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="group" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Grupo</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="roles" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Perfis</span>
                </label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600"
                        data-column="users" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Usuários</span>
                </label>
            </div>
        </x-spire::dropdown>
    </div>

    {{-- Table Container --}}
    <div id="table-container">
        @include('permissions.partials.table', ['permissions' => $permissions])
    </div>

    @push('scripts')
        <script type="module">
            import {
                FilamentTable
            } from '/resources/js/FilamentTable.ts';

            document.addEventListener('DOMContentLoaded', () => {
                const table = new FilamentTable({
                    tableContainerId: 'table-container',
                    searchInputId: 'search-input',
                    baseUrl: '{{ route('permissions.index') }}',
                });

                // Filtro por grupo
                const groupFilter = document.getElementById('group-filter');
                if (groupFilter) {
                    groupFilter.addEventListener('change', function() {
                        const url = new URL(window.location.href);
                        if (this.value) {
                            url.searchParams.set('group', this.value);
                        } else {
                            url.searchParams.delete('group');
                        }
                        url.searchParams.delete('page');
                        table.fetchData(url.toString());
                    });
                }
            });
        </script>
    @endpush
</x-layouts.module>
