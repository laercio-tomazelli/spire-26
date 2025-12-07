@props([
    'name' => 'star',
    'size' => 'md', // xs, sm, md, lg, xl, 2xl
    'class' => '',
])

@php
    $sizes = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8',
        '2xl' => 'w-10 h-10',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<svg {{ $attributes->merge(['class' => "{$sizeClass} {$class}"]) }}>
    <use href="#icon-{{ $name }}"></use>
</svg>
