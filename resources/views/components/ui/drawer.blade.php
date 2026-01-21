@props([
    'id' => 'drawer-' . uniqid(),
    'position' => 'left',
    'width' => 'max-w-sm',
    'title' => '',
    'duration' => '400'
])

@php
    $positionClasses = [
        'left' => 'left-0 top-0 h-full -translate-x-full',
        'right' => 'right-0 top-0 h-full translate-x-full',
        'top' => 'top-0 left-0 w-full -translate-y-full',
        'bottom' => 'bottom-0 left-0 w-full translate-y-full',
    ];
    
    $sizeClasses = [
        'left' => $width . ' w-full',
        'right' => $width . ' w-full',
        'top' => 'max-h-96 h-full',
        'bottom' => 'max-h-96 h-full',
    ];
@endphp

<div 
    {{ $attributes->merge(['class' => 'fixed inset-0 z-50 hidden pointer-events-none']) }}
    data-v="drawer"
    data-position="{{ $position }}"
    data-duration="{{ $duration }}"
    id="{{ $id }}"
    role="dialog"
    aria-modal="true"
>
    {{-- Overlay --}}
    <div 
        data-drawer-overlay
        class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-auto"
        style="transition: opacity {{ $duration }}ms cubic-bezier(0.32, 0.72, 0, 1);"
    ></div>
    
    {{-- Content --}}
    <div 
        data-drawer-content
        class="absolute {{ $positionClasses[$position] }} {{ $sizeClasses[$position] }} bg-white dark:bg-gray-800 shadow-2xl pointer-events-auto flex flex-col"
        style="transition: transform {{ $duration }}ms cubic-bezier(0.32, 0.72, 0, 1);"
        tabindex="-1"
    >
        {{-- Header --}}
        @if($title || isset($header))
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                @if(isset($header))
                    {{ $header }}
                @else
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
                @endif
                <button 
                    type="button" 
                    data-drawer-close
                    class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    aria-label="Fechar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif
        
        {{-- Body --}}
        <div class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </div>
        
        {{-- Footer --}}
        @if(isset($footer))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
