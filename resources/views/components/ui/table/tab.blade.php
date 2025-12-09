@props([
    'active' => false,
    'count' => null,
    'variant' => null, // success, danger, warning, info
])

@php
    $badgeColors = match ($variant) {
        'success' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
        'danger' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
        'warning' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400',
        'info' => 'bg-cyan-50 text-cyan-600 dark:bg-cyan-500/10 dark:text-cyan-400',
        default => $active
            ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400'
            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
    };
@endphp

<button type="button"
    {{ $attributes->merge([
        'class' =>
            'fi-ta-tab relative px-4 py-3 text-sm font-medium transition-colors ' .
            ($active
                ? 'text-blue-600 dark:text-blue-400'
                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'),
    ]) }}>
    <span class="flex items-center gap-x-2">
        {{ $slot }}

        @if ($count !== null)
            <span
                class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-medium {{ $badgeColors }}">
                {{ number_format($count) }}
            </span>
        @endif
    </span>

    @if ($active)
        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 dark:bg-blue-400"></span>
    @endif
</button>
