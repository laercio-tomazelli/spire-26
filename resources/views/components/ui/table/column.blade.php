@props([
    'label',
    'sortable' => false,
    'sortField' => null,
    'align' => 'left', // left, center, right
    'width' => null,
])

@php
    $alignClasses = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };

    $widthStyle = $width ? "width: {$width};" : '';
@endphp

<th
    {{ $attributes->merge([
        'class' => "fi-ta-header-cell px-3 py-3.5 text-sm font-semibold text-gray-950 dark:text-white {$alignClasses}",
        'style' => $widthStyle,
    ]) }}
>
    @if($sortable)
        <button
            type="button"
            class="group inline-flex items-center gap-x-1 whitespace-nowrap"
            x-on:click="sort('{{ $sortField ?? Str::snake($label) }}')"
        >
            {{ $label }}

            <span class="flex-none rounded text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400">
                {{-- Ascending icon --}}
                <svg
                    x-show="sortField === '{{ $sortField ?? Str::snake($label) }}' && sortDirection === 'asc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" />
                </svg>

                {{-- Descending icon --}}
                <svg
                    x-show="sortField === '{{ $sortField ?? Str::snake($label) }}' && sortDirection === 'desc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>

                {{-- Neutral/Unsorted icon --}}
                <svg
                    x-show="sortField !== '{{ $sortField ?? Str::snake($label) }}'"
                    class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    @else
        {{ $label }}
    @endif
</th>
