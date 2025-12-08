@props([
    'label',
    'sortable' => false,
    'sortField' => null,
    'align' => 'left', // left, center, right
    'width' => null,
])

@php
    $alignClasses = match ($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };

    $widthStyle = $width ? "width: {$width};" : '';
    $fieldName = $sortField ?? Str::snake($label);
@endphp

<th
    {{ $attributes->merge([
        'class' => "fi-ta-header-cell px-3 py-3.5 text-sm font-semibold text-gray-950 dark:text-white {$alignClasses}",
        'style' => $widthStyle,
    ]) }}
    data-sort-field="{{ $sortable ? $fieldName : '' }}">
    @if ($sortable)
        <button type="button" class="group inline-flex items-center gap-x-1 whitespace-nowrap"
            x-on:click="$dispatch('table-sort', { field: '{{ $fieldName }}' })">
            {{ $label }}

            <span class="fi-ta-sort-icons flex-none rounded text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400">
                {{-- Sort indicator icons are managed via JS after AJAX --}}
                <svg class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    @else
        {{ $label }}
    @endif
</th>
