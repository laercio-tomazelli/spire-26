@props([
    'align' => 'left', // left, center, right
    'wrap' => false,
])

@php
    $alignClasses = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<td
    {{ $attributes->merge([
        'class' => "fi-ta-cell px-3 py-4 text-sm text-gray-700 dark:text-gray-300 {$alignClasses}" .
            ($wrap ? '' : ' whitespace-nowrap'),
    ]) }}
>
    <div class="fi-ta-col">
        {{ $slot }}
    </div>
</td>
