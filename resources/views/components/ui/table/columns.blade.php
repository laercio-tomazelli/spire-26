@props([
    'selectable' => false,
])

<thead class="bg-gray-50 dark:bg-white/5">
    <tr class="border-b border-gray-200 dark:border-white/10">
        @if ($selectable)
            <th class="fi-ta-header-cell fi-ta-selection-cell w-4 px-3 py-3.5">
                <input type="checkbox"
                    class="fi-checkbox-input fi-ta-select-all h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-primary-600"
                    onclick="window.dispatchEvent(new CustomEvent('table-toggle-page-selection'))" />
            </th>
        @endif

        {{ $slot }}
    </tr>
</thead>
