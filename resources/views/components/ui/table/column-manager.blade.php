@props([
    'columns' => [],
])

<div x-data="{ open: false }" class="relative">
    <button type="button" x-on:click="open = !open"
        class="fi-icon-btn inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-500 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-400"
        title="Column manager">
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M14 17h2.75A2.25 2.25 0 0 0 19 14.75v-9.5A2.25 2.25 0 0 0 16.75 3H14v14ZM12.5 3h-5v14h5V3ZM3.25 3H6v14H3.25A2.25 2.25 0 0 1 1 14.75v-9.5A2.25 2.25 0 0 1 3.25 3Z" />
        </svg>
    </button>

    <div x-show="open" x-on:click.outside="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
        x-cloak>
        <div class="fi-ta-col-manager p-4">
            <div class="fi-ta-col-manager-header mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                    Columns
                </h2>

                <button type="button"
                    class="text-sm font-medium text-danger-600 hover:text-danger-500 dark:text-danger-400"
                    x-on:click="$dispatch('table-reset-columns')">
                    Reset
                </button>
            </div>

            <div class="space-y-2">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
