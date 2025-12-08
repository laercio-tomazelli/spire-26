@props([
    'id' => null,
    'href' => null,
    'icon' => null,
    'label' => '',
    'badge' => null,
    'badgeColor' => 'primary',
    'active' => false,
    'disabled' => false,
    'submenu' => false,
])

@php
    $itemId = $id ?? 'item-' . uniqid();
    $isLink = $href && !$submenu;
    $tag = $isLink ? 'a' : 'button';
    
    $badgeColors = [
        'primary' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
        'success' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
        'warning' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
        'danger' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
        'info' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900 dark:text-cyan-300',
    ];
    
    $badgeClass = $badgeColors[$badgeColor] ?? $badgeColors['primary'];
@endphp

<li 
    data-sidebar-item="{{ $itemId }}"
    class="relative"
>
    <{{ $tag }}
        @if($isLink) href="{{ $href }}" @endif
        @if($submenu) type="button" data-submenu-trigger @endif
        @if($disabled) disabled aria-disabled="true" @endif
        {{ $attributes->merge([
            'class' => 'sidebar-item group flex items-center w-full px-3 py-2.5 rounded-lg text-left
                        transition-all duration-200 ease-out
                        ' . ($active 
                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 font-medium' 
                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800')
                        . ($disabled ? ' opacity-50 cursor-not-allowed pointer-events-none' : ' cursor-pointer')
        ]) }}
    >
        {{-- Icon --}}
        @if($icon)
            <span class="sidebar-item-icon shrink-0 w-6 h-6 flex items-center justify-center {{ $active ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200' }} transition-colors duration-200">
                {!! $icon !!}
            </span>
        @endif

        {{-- Label --}}
        <span class="sidebar-item-text ml-3 whitespace-nowrap transition-all duration-300 ease-out overflow-hidden">
            {{ $label }}
        </span>

        {{-- Badge --}}
        @if($badge)
            <span class="sidebar-item-badge ml-auto px-2 py-0.5 text-xs font-medium rounded-full {{ $badgeClass }} transition-all duration-300">
                {{ $badge }}
            </span>
        @endif

        {{-- Submenu Arrow --}}
        @if($submenu)
            <svg 
                data-arrow
                class="sidebar-item-arrow ml-auto w-4 h-4 text-gray-400 transition-transform duration-300 ease-out" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @endif

        {{-- Tooltip (shown when sidebar is collapsed) --}}
        <div class="sidebar-tooltip hidden absolute left-full ml-2 px-3 py-2 bg-gray-900 dark:bg-gray-700 text-white text-sm rounded-lg whitespace-nowrap opacity-0 pointer-events-none z-50 transition-opacity duration-200">
            {{ $label }}
            <div class="absolute right-full top-1/2 -translate-y-1/2 border-8 border-transparent border-r-gray-900 dark:border-r-gray-700"></div>
        </div>
    </{{ $tag }}>

    {{-- Submenu --}}
    @if($submenu)
        <ul data-submenu class="hidden mt-1 ml-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700 space-y-1">
            {{ $slot }}
        </ul>
    @endif
</li>
