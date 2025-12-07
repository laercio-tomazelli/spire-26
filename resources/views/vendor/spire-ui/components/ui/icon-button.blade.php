@props([
    'variant' => 'ghost', // ghost, filled, outline
    'size' => 'md', // sm, md, lg
    'color' => 'gray', // gray, primary, success, warning, danger
    'rounded' => 'lg', // sm, md, lg, full
    'disabled' => false,
    'title' => null,
])

@php
    $variants = [
        'ghost' => [
            'gray' => 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700',
            'primary' => 'text-blue-500 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/50',
            'success' => 'text-green-500 hover:bg-green-100 dark:text-green-400 dark:hover:bg-green-900/50',
            'warning' => 'text-yellow-500 hover:bg-yellow-100 dark:text-yellow-400 dark:hover:bg-yellow-900/50',
            'danger' => 'text-red-500 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/50',
        ],
        'filled' => [
            'gray' => 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600',
            'primary' => 'bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/50 dark:text-blue-300 dark:hover:bg-blue-900/70',
            'success' => 'bg-green-100 text-green-600 hover:bg-green-200 dark:bg-green-900/50 dark:text-green-300 dark:hover:bg-green-900/70',
            'warning' => 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200 dark:bg-yellow-900/50 dark:text-yellow-300 dark:hover:bg-yellow-900/70',
            'danger' => 'bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/50 dark:text-red-300 dark:hover:bg-red-900/70',
        ],
        'outline' => [
            'gray' => 'border border-gray-300 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-800',
            'primary' => 'border border-blue-300 text-blue-600 hover:bg-blue-50 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/30',
            'success' => 'border border-green-300 text-green-600 hover:bg-green-50 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/30',
            'warning' => 'border border-yellow-300 text-yellow-600 hover:bg-yellow-50 dark:border-yellow-600 dark:text-yellow-400 dark:hover:bg-yellow-900/30',
            'danger' => 'border border-red-300 text-red-600 hover:bg-red-50 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/30',
        ],
    ];

    $sizes = [
        'sm' => 'w-7 h-7',
        'md' => 'w-9 h-9',
        'lg' => 'w-11 h-11',
    ];

    $iconSizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];

    $roundedStyles = [
        'sm' => 'rounded',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'full' => 'rounded-full',
    ];

    $variantColors = $variants[$variant] ?? $variants['ghost'];
    $colorClass = $variantColors[$color] ?? $variantColors['gray'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $iconSizeClass = $iconSizes[$size] ?? $iconSizes['md'];
    $roundedClass = $roundedStyles[$rounded] ?? $roundedStyles['lg'];
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
@endphp

<button 
    type="button"
    @if($title) title="{{ $title }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge([
        'class' => "inline-flex items-center justify-center transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 {$colorClass} {$sizeClass} {$roundedClass} {$disabledClass}"
    ]) }}
>
    <span class="{{ $iconSizeClass }}">
        {{ $slot }}
    </span>
</button>
