@props([
    'field' => null,
    'align' => 'left', // left, center, right
    'compact' => false,
])

@php
    $alignClasses = match ($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };

    $cellPadding = $compact ? 'px-4 py-2' : 'px-6 py-4';
@endphp

<td class="{{ $cellPadding }} {{ $alignClasses }} text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @elseif ($field)
        <span x-text="row.{{ $field }}"></span>
    @endif
</td>
