@props([
    'activeCount' => 0,
])

@php
    $uniqueId = 'fi-ta-filters-' . uniqid();
@endphp

<div class="relative" data-v="dropdown" id="{{ $uniqueId }}">
    <button type="button"
        class="fi-ta-filters-trigger fi-icon-btn relative inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-500 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-400"
        title="Filter" aria-haspopup="true" aria-expanded="false">
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z"
                clip-rule="evenodd" />
        </svg>

        @if ($activeCount > 0)
            <span
                class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-primary-500 px-1 text-[10px] font-medium text-white">
                {{ $activeCount }}
            </span>
        @endif
    </button>

    <div
        class="fi-ta-filters-content hidden absolute right-0 z-10 mt-2 w-72 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-ta-filters p-4">
            <div class="fi-ta-filters-header mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                    Filters
                </h2>

                @isset($reset)
                    {{ $reset }}
                @else
                    <button type="button"
                        class="text-sm font-medium text-danger-600 hover:text-danger-500 dark:text-danger-400"
                        onclick="window.dispatchEvent(new CustomEvent('table-reset-filters'))">
                        Reset
                    </button>
                @endisset
            </div>

            <div class="space-y-4">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/10">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>

<script>
    (function() {
        const container = document.getElementById('{{ $uniqueId }}');
        if (!container) return;

        const trigger = container.querySelector('.fi-ta-filters-trigger');
        const content = container.querySelector('.fi-ta-filters-content');
        let isOpen = false;

        function open() {
            isOpen = true;
            content.classList.remove('hidden');
            trigger.setAttribute('aria-expanded', 'true');
        }

        function close() {
            isOpen = false;
            content.classList.add('hidden');
            trigger.setAttribute('aria-expanded', 'false');
        }

        function toggle() {
            isOpen ? close() : open();
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            toggle();
        });

        document.addEventListener('click', (e) => {
            if (isOpen && !container.contains(e.target)) {
                close();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (isOpen && e.key === 'Escape') {
                close();
                trigger.focus();
            }
        });
    })();
</script>
