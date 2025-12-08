@props([
    'record' => null,
    'recordKey' => null,
    'selectable' => false,
    'clickable' => false,
    'href' => null,
])

@php
    $key = $recordKey ?? ($record?->getKey() ?? uniqid());
@endphp

<tr {{ $attributes->merge([
    'class' =>
        'fi-ta-row border-b border-gray-200 transition-colors dark:border-white/5' .
        ($clickable || $href ? ' cursor-pointer hover:bg-gray-50 dark:hover:bg-white/5' : '') .
        ' group',
]) }}
    data-record-key="{{ $key }}" x-data>
    @if ($selectable)
        <td class="fi-ta-cell fi-ta-selection-cell w-4 px-3 py-4">
            <input type="checkbox" value="{{ $key }}"
                class="fi-checkbox-input fi-ta-record-checkbox h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-primary-600"
                x-on:click.stop="$dispatch('table-toggle-selection', { key: '{{ $key }}' })" />
        </td>
    @endif

    {{ $slot }}
</tr>
