@props([
    'type' => 'info', // info, success, warning, danger
    'title' => null,
    'icon' => true, // mostrar ícone
    'dismissible' => false, // botão de fechar
    'variant' => 'solid', // solid, soft, outline, minimal
    'size' => 'md', // sm, md, lg
])

@php
    $types = [
        'info' => [
            'solid' => 'bg-blue-600 text-white',
            'soft' => 'bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            'outline' => 'border-2 border-blue-500 text-blue-700 dark:text-blue-400',
            'minimal' => 'text-blue-700 dark:text-blue-400',
        ],
        'success' => [
            'solid' => 'bg-green-600 text-white',
            'soft' => 'bg-green-50 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'outline' => 'border-2 border-green-500 text-green-700 dark:text-green-400',
            'minimal' => 'text-green-700 dark:text-green-400',
        ],
        'warning' => [
            'solid' => 'bg-yellow-500 text-white',
            'soft' => 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'outline' => 'border-2 border-yellow-500 text-yellow-700 dark:text-yellow-400',
            'minimal' => 'text-yellow-700 dark:text-yellow-400',
        ],
        'danger' => [
            'solid' => 'bg-red-600 text-white',
            'soft' => 'bg-red-50 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'outline' => 'border-2 border-red-500 text-red-700 dark:text-red-400',
            'minimal' => 'text-red-700 dark:text-red-400',
        ],
    ];

    $icons = [
        'info' => '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
        'success' => '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
        'warning' => '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
        'danger' => '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
    ];

    $sizes = [
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-3 text-sm',
        'lg' => 'px-5 py-4 text-base',
    ];

    $closeColors = [
        'info' => $variant === 'solid' ? 'hover:bg-blue-700' : 'hover:bg-blue-100 dark:hover:bg-blue-800',
        'success' => $variant === 'solid' ? 'hover:bg-green-700' : 'hover:bg-green-100 dark:hover:bg-green-800',
        'warning' => $variant === 'solid' ? 'hover:bg-yellow-600' : 'hover:bg-yellow-100 dark:hover:bg-yellow-800',
        'danger' => $variant === 'solid' ? 'hover:bg-red-700' : 'hover:bg-red-100 dark:hover:bg-red-800',
    ];

    $typeStyles = $types[$type] ?? $types['info'];
    $variantClass = $typeStyles[$variant] ?? $typeStyles['soft'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $iconSvg = $icons[$type] ?? $icons['info'];
    $closeColor = $closeColors[$type] ?? $closeColors['info'];
@endphp

<div 
    role="alert"
    {{ $attributes->merge(['class' => "rounded-lg {$variantClass} {$sizeClass}"]) }}
>
    <div class="flex items-start gap-3">
        {{-- Icon --}}
        @if($icon)
            <span class="mt-0.5 opacity-80">
                {!! $iconSvg !!}
            </span>
        @endif

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            @if($title)
                <h4 class="font-semibold mb-1">{{ $title }}</h4>
            @endif
            <div class="{{ $title ? 'opacity-90' : '' }}">
                {{ $slot }}
            </div>
        </div>

        {{-- Dismiss Button --}}
        @if($dismissible)
            <button 
                type="button"
                class="flex-shrink-0 p-1 rounded-lg transition-colors opacity-70 hover:opacity-100 {{ $closeColor }}"
                onclick="this.closest('[role=alert]').remove()"
                aria-label="Fechar"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        @endif
    </div>

    {{-- Actions slot --}}
    @isset($actions)
        <div class="mt-3 flex items-center gap-2 {{ $icon ? 'ml-8' : '' }}">
            {{ $actions }}
        </div>
    @endisset
</div>
