@props([
    'columns' => [],
])

@php
    $uniqueId = 'fi-ta-col-manager-' . uniqid();
@endphp

<div class="relative" data-v="dropdown" id="{{ $uniqueId }}">
    <button type="button"
        class="fi-ta-col-manager-trigger fi-icon-btn inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-500 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-400"
        title="Column manager" aria-haspopup="true" aria-expanded="false">
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M14 17h2.75A2.25 2.25 0 0 0 19 14.75v-9.5A2.25 2.25 0 0 0 16.75 3H14v14ZM12.5 3h-5v14h5V3ZM3.25 3H6v14H3.25A2.25 2.25 0 0 1 1 14.75v-9.5A2.25 2.25 0 0 1 3.25 3Z" />
        </svg>
    </button>

    <div
        class="fi-ta-col-manager-content hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-ta-col-manager p-4">
            <div class="fi-ta-col-manager-header mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                    Columns
                </h2>

                <button type="button"
                    class="text-sm font-medium text-danger-600 hover:text-danger-500 dark:text-danger-400"
                    onclick="window.dispatchEvent(new CustomEvent('table-reset-columns'))">
                    Reset
                </button>
            </div>

            <div class="space-y-2">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const container = document.getElementById('{{ $uniqueId }}');
        if (!container) return;

        const trigger = container.querySelector('.fi-ta-col-manager-trigger');
        const content = container.querySelector('.fi-ta-col-manager-content');
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
