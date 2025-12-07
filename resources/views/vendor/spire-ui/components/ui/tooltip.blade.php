@props([
    'content' => '',
    'position' => 'top'
])

<span 
    {{ $attributes->merge(['class' => 'inline-block']) }}
    data-v="tooltip"
    data-tooltip="{{ $content }}"
    data-tooltip-position="{{ $position }}"
>
    {{ $slot }}
</span>
