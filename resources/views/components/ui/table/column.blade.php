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

    // Check if this column is currently sorted
    $currentSortField = request('sort', '');
    $currentSortDirection = request('direction', 'asc');
    $isCurrentlySorted = $sortable && $currentSortField === $fieldName;
@endphp

<th {{ $attributes->merge([
    'class' => "fi-ta-header-cell px-3 py-3.5 text-sm font-semibold text-gray-950 dark:text-white {$alignClasses}",
    'style' => $widthStyle,
]) }}
    data-sort-field="{{ $sortable ? $fieldName : '' }}">
    @if ($sortable)
        <button type="button" class="group inline-flex items-center gap-x-1 whitespace-nowrap"
            onclick="window.dispatchEvent(new CustomEvent('table-sort', { detail: { field: '{{ $fieldName }}' } }))">
            {{ $label }}

            <span
                class="fi-ta-sort-icons flex-none rounded {{ $isCurrentlySorted ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400' }}">
                @if ($isCurrentlySorted && $currentSortDirection === 'asc')
                    {{-- Ascending icon --}}
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3z"
                            clip-rule="evenodd" />
                    </svg>
                @elseif ($isCurrentlySorted && $currentSortDirection === 'desc')
                    {{-- Descending icon --}}
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 17a.75.75 0 01-.55-.24l-3.25-3.5a.75.75 0 111.1-1.02l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5A.75.75 0 0110 17z"
                            clip-rule="evenodd" />
                    </svg>
                @else
                    {{-- Default unsorted icon (shows on hover) --}}
                    <svg class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                @endif
            </span>
        </button>
    @else
        {{ $label }}
    @endif
</th>
