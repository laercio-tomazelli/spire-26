@props([
    'active' => false,
    'count' => null,
])

<button type="button"
    {{ $attributes->merge([
        'class' =>
            'fi-ta-tab relative px-4 py-3 text-sm font-medium transition-colors ' .
            ($active
                ? 'text-primary-600 dark:text-primary-400'
                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'),
    ]) }}>
    <span class="flex items-center gap-x-2">
        {{ $slot }}

        @if ($count !== null)
            <span @class([
                'inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-medium',
                'bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400' => $active,
                'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' => !$active,
            ])>
                {{ number_format($count) }}
            </span>
        @endif
    </span>

    @if ($active)
        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-primary-600 dark:bg-primary-400"></span>
    @endif
</button>
