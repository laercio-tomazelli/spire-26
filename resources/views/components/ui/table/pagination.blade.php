@props([
    'paginator' => null,
    'perPageOptions' => [5, 10, 25, 50],
    'showPerPage' => true,
    'showInfo' => true,
])

@php
    $currentPage = $paginator?->currentPage() ?? 1;
    $lastPage = $paginator?->lastPage() ?? 1;
    $total = $paginator?->total() ?? 0;
    $from = $paginator?->firstItem() ?? 0;
    $to = $paginator?->lastItem() ?? 0;
    $perPage = $paginator?->perPage() ?? 10;
@endphp

<nav {{ $attributes->merge([
    'class' =>
        'fi-ta-pagination flex items-center justify-between gap-x-4 border-t border-gray-200 px-4 py-3 dark:border-white/10 sm:px-6',
]) }}
    aria-label="Pagination">
    {{-- Info --}}
    @if ($showInfo && $paginator)
        <span class="fi-pagination-overview hidden text-sm text-gray-700 dark:text-gray-400 sm:block">
            Showing <span class="font-medium">{{ number_format($from) }}</span>
            to <span class="font-medium">{{ number_format($to) }}</span>
            of <span class="font-medium">{{ number_format($total) }}</span> results
        </span>
    @endif

    <div class="flex items-center gap-x-4">
        {{-- Per page selector --}}
        @if ($showPerPage)
            <div class="fi-pagination-records-per-page-select-ctn flex items-center gap-x-2">
                <label for="per-page" class="text-sm text-gray-700 dark:text-gray-400">Per page</label>
                <select id="per-page"
                    class="fi-select-input rounded-lg border-gray-300 py-1.5 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300"
                    x-model="perPage" x-on:change="changePerPage($event.target.value)">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" @selected($perPage == $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Page numbers --}}
        @if ($paginator && $lastPage > 1)
            <ol class="fi-pagination-items flex items-center gap-x-1">
                {{-- Previous --}}
                <li>
                    <button type="button"
                        class="fi-pagination-item-btn relative inline-flex items-center rounded-lg p-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                        @disabled($currentPage <= 1) x-on:click="previousPage()">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </li>

                {{-- Page numbers --}}
                @php
                    $window = 2;
                    $pages = [];

                    // First pages
                    for ($i = 1; $i <= min(2, $lastPage); $i++) {
                        $pages[] = $i;
                    }

                    // Pages around current
                    for ($i = max(1, $currentPage - $window); $i <= min($lastPage, $currentPage + $window); $i++) {
                        if (!in_array($i, $pages)) {
                            $pages[] = $i;
                        }
                    }

                    // Last pages
                    for ($i = max(1, $lastPage - 1); $i <= $lastPage; $i++) {
                        if (!in_array($i, $pages)) {
                            $pages[] = $i;
                        }
                    }

                    sort($pages);
                @endphp

                @foreach ($pages as $index => $page)
                    @if ($index > 0 && $page - $pages[$index - 1] > 1)
                        <li class="fi-pagination-item fi-disabled">
                            <span class="px-2 text-gray-500 dark:text-gray-400">...</span>
                        </li>
                    @endif

                    <li>
                        <button type="button"
                            class="fi-pagination-item-btn relative inline-flex h-8 min-w-8 items-center justify-center rounded-lg px-2.5 text-sm font-medium transition-colors
                                {{ $page === $currentPage
                                    ? 'bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}"
                            x-on:click="gotoPage({{ $page }})">
                            {{ $page }}
                        </button>
                    </li>
                @endforeach

                {{-- Next --}}
                <li>
                    <button type="button"
                        class="fi-pagination-item-btn relative inline-flex items-center rounded-lg p-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                        @disabled($currentPage >= $lastPage) x-on:click="nextPage()">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </li>
            </ol>
        @endif
    </div>
</nav>
