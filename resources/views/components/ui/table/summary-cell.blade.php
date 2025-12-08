@props([
    'label' => null,
    'value' => null,
    'align' => 'left',
])

@php
    $alignClasses = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<td class="fi-ta-cell px-3 py-2 {{ $alignClasses }}">
    <div class="fi-ta-text-summary">
        @if($label)
            <span class="fi-ta-text-summary-label block text-xs text-gray-500 dark:text-gray-400">
                {{ $label }}
            </span>
        @endif
        <span class="text-sm font-medium text-gray-900 dark:text-white">
            {{ $value ?? $slot }}
        </span>
    </div>
</td>
