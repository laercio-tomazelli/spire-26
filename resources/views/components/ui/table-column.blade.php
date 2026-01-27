@props([
    'field' => null,
    'label' => null,
    'sortable' => false,
    'width' => 'auto',
    'align' => 'left', // left, center, right
    'compact' => false,
])

@php
    $alignClasses = match ($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };

    $headerPadding = $compact ? 'px-4 py-2' : 'px-6 py-3';
    $widthStyle = $width !== 'auto' ? "width: {$width};" : '';
@endphp

<th
    scope="col"
    class="{{ $headerPadding }} {{ $alignClasses }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
    @if ($widthStyle) style="{{ $widthStyle }}" @endif
>
    @if ($sortable && $field)
        <button
            type="button"
            @click="sort('{{ $field }}')"
            class="group inline-flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200"
        >
            <span>{{ $label }}</span>
            <span class="flex-none">
                <svg
                    class="h-4 w-4 transition-transform"
                    :class="{
                        'text-blue-500': sortField === '{{ $field }}',
                        'text-gray-400': sortField !== '{{ $field }}',
                        'rotate-180': sortField === '{{ $field }}' && sortDirection === 'desc'
                    }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </span>
        </button>
    @else
        {{ $label }}
    @endif
</th>
