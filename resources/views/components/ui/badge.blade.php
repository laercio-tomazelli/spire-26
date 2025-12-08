@props([
    'variant' => 'default', // default, primary, success, warning, danger, info
    'size' => 'md', // sm, md, lg
    'rounded' => 'full', // full, md, sm, none
    'dot' => false, // mostra um dot antes do texto
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

    $dotColors = [
        'default' => 'bg-gray-500',
        'primary' => 'bg-blue-500',
        'success' => 'bg-green-500',
        'warning' => 'bg-yellow-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-cyan-500',
    ];

    $sizes = [
        'sm' => 'text-xs px-2 py-0.5',
        'md' => 'text-xs px-2.5 py-1',
        'lg' => 'text-sm px-3 py-1.5',
    ];

    $roundedStyles = [
        'full' => 'rounded-full',
        'md' => 'rounded-md',
        'sm' => 'rounded',
        'none' => 'rounded-none',
    ];

    $variantClass = $variants[$variant] ?? $variants['default'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $roundedClass = $roundedStyles[$rounded] ?? $roundedStyles['full'];
    $dotColor = $dotColors[$variant] ?? $dotColors['default'];
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1.5 font-medium whitespace-nowrap {$variantClass} {$sizeClass} {$roundedClass}" . ($pulse ? ' animate-pulse' : '')
]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
    @endif
    
    {{ $slot }}
    
    @if($removable)
        <button type="button" 
                class="ml-0.5 -mr-1 hover:opacity-70 transition-opacity focus:outline-none"
                onclick="this.parentElement.remove()">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif
</span>
