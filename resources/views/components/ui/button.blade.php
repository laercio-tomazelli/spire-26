@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, ghost, danger, success, warning
    'size' => 'md', // sm, md, lg
    'disabled' => false,
])

@php
    $variants = [
        'primary' => 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        'secondary' =>
            'text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:ring-gray-500',
        'ghost' => 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500',
        'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'success' => 'text-white bg-green-600 hover:bg-green-700 focus:ring-green-500',
        'warning' => 'text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
    ];

    $sizes = [
        'sm' => 'h-8 px-3 text-sm',
        'md' => 'h-9 px-4 text-sm',
        'lg' => 'h-11 px-5 text-base',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $baseClass = "inline-flex items-center justify-center {$sizeClass} rounded-lg font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed {$variantClass}";
@endphp

@if ($href)
    <a href="{{ $href }}" data-v="button" {{ $attributes->merge(['class' => $baseClass]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" data-v="button" @if ($disabled) disabled @endif
        {{ $attributes->merge(['class' => $baseClass]) }}>
        {{ $slot }}
    </button>
@endif
