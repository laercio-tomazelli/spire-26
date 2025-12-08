@props([
    'id' => null,
    'title' => null,
    'open' => false,
    'icon' => true, // mostra ícone de seta
    'iconPosition' => 'right', // left, right
    'variant' => 'default', // default, bordered, ghost
    'disabled' => false,
])

@php
    $collapseId = $id ?? 'collapse-' . uniqid();
    
    $variants = [
        'default' => 'bg-white dark:bg-gray-800 shadow-sm rounded-lg',
        'bordered' => 'border border-gray-200 dark:border-gray-700 rounded-lg',
        'ghost' => '',
    ];
    
    $headerVariants = [
        'default' => 'px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50',
        'bordered' => 'px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50',
        'ghost' => 'py-2 hover:text-blue-600 dark:hover:text-blue-400',
    ];
    
    $contentVariants = [
        'default' => 'px-4 pb-4',
        'bordered' => 'px-4 pb-4',
        'ghost' => 'py-2',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['default'];
    $headerClass = $headerVariants[$variant] ?? $headerVariants['default'];
    $contentClass = $contentVariants[$variant] ?? $contentVariants['default'];
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer';
@endphp

<div 
    data-collapse="{{ $collapseId }}"
    {{ $attributes->merge(['class' => $variantClass]) }}
>
    {{-- Header/Trigger --}}
    <button
        type="button"
        class="w-full flex items-center justify-between gap-3 text-left font-medium text-gray-900 dark:text-white transition-colors {{ $headerClass }} {{ $disabledClass }} {{ $variant !== 'ghost' ? 'rounded-lg' : '' }}"
        @if(!$disabled)
            onclick="VanillaPro.collapse('{{ $collapseId }}').toggle()"
        @endif
        aria-expanded="{{ $open ? 'true' : 'false' }}"
        aria-controls="{{ $collapseId }}-content"
    >
        @if($icon && $iconPosition === 'left')
            <svg 
                class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200 flex-shrink-0"
                data-collapse-icon
                style="{{ $open ? 'transform: rotate(180deg)' : '' }}"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        @endif

        <span class="flex-1">
            @isset($trigger)
                {{ $trigger }}
            @else
                {{ $title }}
            @endisset
        </span>

        @if($icon && $iconPosition === 'right')
            <svg 
                class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200 flex-shrink-0"
                data-collapse-icon
                style="{{ $open ? 'transform: rotate(180deg)' : '' }}"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        @endif
    </button>

    {{-- Content --}}
    <div 
        id="{{ $collapseId }}-content"
        class="overflow-hidden transition-all duration-300 ease-in-out"
        style="{{ $open ? '' : 'max-height: 0; opacity: 0;' }}"
        data-collapse-content
    >
        <div class="{{ $contentClass }}">
            {{ $slot }}
        </div>
    </div>
</div>
