@props([
    'label' => null,
])

<tr class="fi-ta-row fi-ta-summary-row border-b border-gray-100 dark:border-white/5">
    @if($label)
        <td class="fi-ta-cell fi-ta-summary-row-heading-cell px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300" {{ $attributes->only('colspan') }}>
            {{ $label }}
        </td>
    @endif

    {{ $slot }}
</tr>
