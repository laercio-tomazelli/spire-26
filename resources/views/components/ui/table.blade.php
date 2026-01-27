@props([
    'data' => [],
    'searchable' => false,
    'sortable' => false,
    'paginated' => false,
    'perPage' => 10,
    'loading' => false,
    'emptyMessage' => 'Nenhum resultado encontrado',
    'striped' => false,
    'hoverable' => true,
    'compact' => false,
])

@php
    $tableId = 'table-' . uniqid();
    $rowClasses = '';
    if ($striped) {
        $rowClasses .= ' even:bg-gray-50 dark:even:bg-gray-800/50';
    }
    if ($hoverable) {
        $rowClasses .= ' hover:bg-gray-50 dark:hover:bg-gray-800/50';
    }
    $cellPadding = $compact ? 'px-4 py-2' : 'px-6 py-4';
    $headerPadding = $compact ? 'px-4 py-2' : 'px-6 py-3';
@endphp

<div
    x-data="{
        data: @js($data),
        filteredData: @js($data),
        searchQuery: '',
        sortField: null,
        sortDirection: 'asc',
        currentPage: 1,
        perPage: {{ $perPage }},

        get paginatedData() {
            @if ($paginated)
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredData.slice(start, start + this.perPage);
            @else
                return this.filteredData;
            @endif
        },

        get totalPages() {
            return Math.ceil(this.filteredData.length / this.perPage);
        },

        search() {
            if (!this.searchQuery.trim()) {
                this.filteredData = [...this.data];
            } else {
                const query = this.searchQuery.toLowerCase();
                this.filteredData = this.data.filter(row => {
                    return Object.values(row).some(value =>
                        String(value).toLowerCase().includes(query)
                    );
                });
            }
            this.currentPage = 1;
        },

        sort(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }

            this.filteredData.sort((a, b) => {
                let valueA = a[field];
                let valueB = b[field];

                if (typeof valueA === 'string') valueA = valueA.toLowerCase();
                if (typeof valueB === 'string') valueB = valueB.toLowerCase();

                if (valueA < valueB) return this.sortDirection === 'asc' ? -1 : 1;
                if (valueA > valueB) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        }
    }"
    class="w-full"
    id="{{ $tableId }}"
>
    {{-- Search Bar --}}
    @if ($searchable)
        <div class="mb-4">
            <div class="relative">
                <input
                    type="text"
                    x-model="searchQuery"
                    @input="search()"
                    placeholder="Buscar..."
                    class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    @if ($loading)
        <div class="relative">
            <div class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 flex items-center justify-center z-10">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    {{ $slot }}
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                <template x-if="paginatedData.length === 0">
                    <tr>
                        <td colspan="100%" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                </template>
                <template x-for="(row, index) in paginatedData" :key="index">
                    <tr class="{{ $rowClasses }}">
                        {{ $row ?? '' }}
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($paginated)
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <span x-text="'Mostrando ' + ((currentPage - 1) * perPage + 1) + ' a ' + Math.min(currentPage * perPage, filteredData.length) + ' de ' + filteredData.length + ' resultados'"></span>
            </div>
            <div class="flex items-center space-x-2">
                <button
                    @click="goToPage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Anterior
                </button>
                <span class="text-sm text-gray-700 dark:text-gray-300" x-text="currentPage + ' de ' + totalPages"></span>
                <button
                    @click="goToPage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Próximo
                </button>
            </div>
        </div>
    @endif
</div>
