@props([
    'position' => 'top-right', // top-left, top-right, bottom-left, bottom-right, top-center, bottom-center
])

@php
    $positionClasses = match ($position) {
        'top-left' => 'top-4 left-4',
        'top-center' => 'top-4 left-1/2 -translate-x-1/2',
        'bottom-left' => 'bottom-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-center' => 'bottom-4 left-1/2 -translate-x-1/2',
        default => 'top-4 right-4',
    };

    $isBottom = str_contains($position, 'bottom');
@endphp

<div
    x-data="{
        toasts: [],
        toastId: 0,

        add(message, type = 'info', duration = 4000) {
            const id = ++this.toastId;
            this.toasts.push({ id, message, type, show: true });

            if (duration > 0) {
                setTimeout(() => {
                    this.remove(id);
                }, duration);
            }

            return id;
        },

        remove(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index > -1) {
                this.toasts[index].show = false;
                setTimeout(() => {
                    this.toasts.splice(index, 1);
                }, 300);
            }
        },

        success(message, duration = 4000) {
            return this.add(message, 'success', duration);
        },

        error(message, duration = 4000) {
            return this.add(message, 'error', duration);
        },

        warning(message, duration = 4000) {
            return this.add(message, 'warning', duration);
        },

        info(message, duration = 4000) {
            return this.add(message, 'info', duration);
        }
    }"
    @toast.window="add($event.detail.message, $event.detail.type || 'info', $event.detail.duration || 4000)"
    class="fixed {{ $positionClasses }} z-50 flex flex-col gap-3 pointer-events-none"
    style="max-width: 24rem;"
    {{ $attributes }}
>
    {{-- Static toasts from slot --}}
    {{ $slot }}

    {{-- Dynamic toasts --}}
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform {{ $isBottom ? 'translate-y-4' : '-translate-y-4' }}"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform {{ $isBottom ? 'translate-y-4' : '-translate-y-4' }}"
            class="w-full max-w-sm rounded-lg shadow-lg pointer-events-auto border"
            :class="{
                'bg-green-50 dark:bg-green-900/50 border-green-200 dark:border-green-800': toast.type === 'success',
                'bg-red-50 dark:bg-red-900/50 border-red-200 dark:border-red-800': toast.type === 'error',
                'bg-yellow-50 dark:bg-yellow-900/50 border-yellow-200 dark:border-yellow-800': toast.type === 'warning',
                'bg-blue-50 dark:bg-blue-900/50 border-blue-200 dark:border-blue-800': toast.type === 'info'
            }"
            role="alert"
        >
            <div class="p-4">
                <div class="flex items-start">
                    {{-- Icon --}}
                    <div class="flex-shrink-0">
                        {{-- Success Icon --}}
                        <svg x-show="toast.type === 'success'" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{-- Error Icon --}}
                        <svg x-show="toast.type === 'error'" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{-- Warning Icon --}}
                        <svg x-show="toast.type === 'warning'" class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{-- Info Icon --}}
                        <svg x-show="toast.type === 'info'" class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="ml-3 flex-1">
                        <p
                            class="text-sm font-medium"
                            :class="{
                                'text-green-800 dark:text-green-200': toast.type === 'success',
                                'text-red-800 dark:text-red-200': toast.type === 'error',
                                'text-yellow-800 dark:text-yellow-200': toast.type === 'warning',
                                'text-blue-800 dark:text-blue-200': toast.type === 'info'
                            }"
                            x-text="toast.message"
                        ></p>
                    </div>

                    {{-- Close Button --}}
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            @click="remove(toast.id)"
                            class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            :class="{
                                'text-green-800 dark:text-green-200 hover:text-green-600': toast.type === 'success',
                                'text-red-800 dark:text-red-200 hover:text-red-600': toast.type === 'error',
                                'text-yellow-800 dark:text-yellow-200 hover:text-yellow-600': toast.type === 'warning',
                                'text-blue-800 dark:text-blue-200 hover:text-blue-600': toast.type === 'info'
                            }"
                        >
                            <span class="sr-only">Fechar</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
