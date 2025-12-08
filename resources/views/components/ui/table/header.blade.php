@props([
    'search' => false,
    'searchPlaceholder' => 'Search',
    'filters' => false,
    'columnManager' => false,
    'bulkActions' => false,
])

<div class="fi-ta-header-ctn divide-y divide-gray-200 dark:divide-white/10">
    {{-- Tabs slot --}}
    @isset($tabs)
        <div class="fi-ta-tabs">
            {{ $tabs }}
        </div>
    @endisset

    {{-- Toolbar --}}
    <div class="fi-ta-header-toolbar flex items-center justify-between gap-x-4 px-4 py-3 sm:px-6">
        {{-- Left side: Bulk actions, grouping --}}
        <div class="fi-ta-actions flex items-center gap-x-4">
            {{-- Bulk actions slot --}}
            @isset($bulkActions)
                <div x-show="selectedCount > 0" x-cloak class="flex items-center gap-2">
                    {{ $bulkActions }}
                </div>
            @endisset

            {{-- Grouping slot --}}
            @isset($grouping)
                {{ $grouping }}
            @endisset

            {{ $leftActions ?? '' }}
        </div>

        {{-- Right side: Search, filters, column manager --}}
        <div class="flex items-center gap-x-3">
            {{-- Search --}}
            @if ($search)
                <div class="fi-ta-search-field">
                    <x-ui.input type="search" :placeholder="$searchPlaceholder" x-model.debounce.500ms="search"
                        icon='<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg>'
                        class="w-64" />
                </div>
            @endif

            {{-- Filters slot --}}
            @isset($filters)
                {{ $filters }}
            @endisset

            {{-- Column manager slot --}}
            @isset($columnManager)
                {{ $columnManager }}
            @endisset

            {{ $rightActions ?? '' }}
        </div>
    </div>

    {{-- Selection indicator --}}
    <div x-show="selectedCount > 0" x-cloak class="fi-ta-selection-indicator bg-gray-50 px-4 py-2 dark:bg-white/5">
        <div class="flex items-center justify-between gap-x-4">
            <div class="flex items-center gap-x-2 text-sm text-gray-700 dark:text-gray-200">
                <svg x-show="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span x-text="selectedCount + ' ' + (selectedCount === 1 ? 'record' : 'records') + ' selected'"></span>
            </div>

            <div class="flex items-center gap-x-3">
                <button type="button" x-show="selectedCount < totalRecords" x-on:click="selectAll()"
                    class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                    Select all <span x-text="totalRecords"></span>
                </button>
                <button type="button" x-on:click="deselectAll()"
                    class="text-sm font-medium text-danger-600 hover:text-danger-500 dark:text-danger-400 dark:hover:text-danger-300">
                    Deselect all
                </button>
            </div>
        </div>
    </div>
</div>
