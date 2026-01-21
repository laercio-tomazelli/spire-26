@props([
    'variant' => 'default', // default, primary, success, warning, danger, info
    'size' => 'md', // sm, md, lg
    'icon' => null, // SVG icon to show before text
    'removable' => false, // mostra botão de remover
    'pulse' => false, // animação de pulse
])

@php
    $variants = [
        'default' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
        'primary' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
        'success' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
        'warning' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
        'danger' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
        'info' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300',
    ];

    $sizes = [
        'sm' => 'h-4 text-[10px] px-1.5 gap-1',
        'md' => 'h-5 text-xs px-2 gap-1',
        'lg' => 'h-6 text-sm px-2.5 gap-1.5',
    ];

    $iconSizes = [
        'sm' => 'w-3.5 h-3.5',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
    ];

    $variantClass = $variants[$variant] ?? $variants['default'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $iconSize = $iconSizes[$size] ?? $iconSizes['md'];
@endphp

<span
    {{ $attributes->merge([
        'class' =>
            "inline-flex items-center font-medium whitespace-nowrap rounded-md {$variantClass} {$sizeClass}" .
            ($pulse ? ' animate-pulse' : ''),
    ]) }}>
    @if ($icon)
        <span class="{{ $iconSize }} shrink-0">{!! $icon !!}</span>
    @endif

    {{ $slot }}

    @if ($removable)
        <button type="button" class="-mr-0.5 hover:opacity-70 transition-opacity focus:outline-none"
            onclick="this.parentElement.remove()">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</span>
