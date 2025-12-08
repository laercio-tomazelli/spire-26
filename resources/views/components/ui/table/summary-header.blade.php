@props([
    'colspan' => 1,
    'heading' => 'Summary',
])

<tr class="fi-ta-row fi-ta-summary-header-row bg-gray-50 dark:bg-white/5">
    <td colspan="{{ $colspan }}" class="fi-ta-cell px-3 py-3 text-sm font-semibold text-gray-950 dark:text-white">
        {{ $heading }}
    </td>
    {{ $slot }}
</tr>
