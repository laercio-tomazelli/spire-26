@props([
    'message' => '',
    'type' => 'info', // success, error, warning, info
    'duration' => 4000,
    'position' => 'top-right', // top-left, top-right, bottom-left, bottom-right
    'dismissible' => true,
    'show' => true,
    'icon' => null,
])

@php
    $toastId = 'toast-' . uniqid();

    $typeConfig = match ($type) {
        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-900/50',
            'border' => 'border-green-200 dark:border-green-800',
            'text' => 'text-green-800 dark:text-green-200',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'iconColor' => 'text-green-500',
        ],
        'error' => [
            'bg' => 'bg-red-50 dark:bg-red-900/50',
            'border' => 'border-red-200 dark:border-red-800',
            'text' => 'text-red-800 dark:text-red-200',
            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'iconColor' => 'text-red-500',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50 dark:bg-yellow-900/50',
            'border' => 'border-yellow-200 dark:border-yellow-800',
            'text' => 'text-yellow-800 dark:text-yellow-200',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'iconColor' => 'text-yellow-500',
        ],
        default => [
            'bg' => 'bg-blue-50 dark:bg-blue-900/50',
            'border' => 'border-blue-200 dark:border-blue-800',
            'text' => 'text-blue-800 dark:text-blue-200',
            'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'iconColor' => 'text-blue-500',
        ],
    };
@endphp

<div
    x-data="{
        show: {{ $show ? 'true' : 'false' }},
        duration: {{ $duration }},
        init() {
            if (this.duration > 0) {
                setTimeout(() => {
                    this.close();
                }, this.duration);
            }
        },
        close() {
            this.show = false;
            this.$dispatch('close');
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-4"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-4"
    id="{{ $toastId }}"
    class="w-full max-w-sm {{ $typeConfig['bg'] }} {{ $typeConfig['border'] }} border rounded-lg shadow-lg pointer-events-auto"
    role="alert"
    {{ $attributes }}
>
    <div class="p-4">
        <div class="flex items-start">
            {{-- Icon --}}
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 {{ $typeConfig['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeConfig['icon'] }}" />
                </svg>
            </div>

            {{-- Content --}}
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium {{ $typeConfig['text'] }}">
                    {{ $message }}
                </p>
                @if ($slot->isNotEmpty())
                    <div class="mt-1 text-sm {{ $typeConfig['text'] }} opacity-90">
                        {{ $slot }}
                    </div>
                @endif
            </div>

            {{-- Close Button --}}
            @if ($dismissible)
                <div class="ml-4 flex-shrink-0 flex">
                    <button
                        @click="close()"
                        class="inline-flex {{ $typeConfig['text'] }} hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-md"
                    >
                        <span class="sr-only">Fechar</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
