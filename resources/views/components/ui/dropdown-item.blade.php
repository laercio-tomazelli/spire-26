@props([
    'href' => null,
    'icon' => null,
    'disabled' => false,
    'danger' => false,
    'active' => false,
])

@php
    $baseClasses = 'w-full flex items-center gap-3 px-4 py-2 text-sm transition-colors duration-150 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700';
    
    $stateClasses = match(true) {
        $disabled => 'text-gray-400 dark:text-gray-500 cursor-not-allowed',
        $danger => 'text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20',
        $active => 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20',
        default => 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700',
    };
    
    $tag = $href && !$disabled ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if($href && !$disabled) href="{{ $href }}" @endif
    @if($tag === 'button') type="button" @endif
    @if($disabled) disabled aria-disabled="true" @endif
    data-dropdown-item
    role="menuitem"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $stateClasses]) }}
    @if(!$disabled)
    onclick="
        const menu = this.closest('[data-dropdown-menu]');
        menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
        menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
    "
    @endif
>
    @if($icon)
        <span class="shrink-0 w-5 h-5">{!! $icon !!}</span>
    @endif
    <span class="flex-1 text-left">{{ $slot }}</span>
    @if($active)
        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    @endif
</{{ $tag }}>
