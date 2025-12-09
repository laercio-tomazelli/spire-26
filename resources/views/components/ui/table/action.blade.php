@props([
    'href' => null,
    'icon' => null,
    'color' => 'primary', // primary, danger, warning, success, info
    'tooltip' => null,
])

@php
    $colorClasses = match ($color) {
        'danger' => 'text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300',
        'warning' => 'text-yellow-600 hover:text-yellow-500 dark:text-yellow-400 dark:hover:text-yellow-300',
        'success' => 'text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300',
        'info' => 'text-cyan-600 hover:text-cyan-500 dark:text-cyan-400 dark:hover:text-cyan-300',
        default => 'text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" @if ($tooltip) title="{{ $tooltip }}" @endif
        {{ $attributes->merge([
            'class' => "fi-ta-action inline-flex items-center justify-center p-1 rounded transition-colors {$colorClasses}",
        ]) }}>
        @if ($icon)
            <span class="h-5 w-5 shrink-0">{!! $icon !!}</span>
        @endif
    </a>
@else
    <button type="button" @if ($tooltip) title="{{ $tooltip }}" @endif
        {{ $attributes->merge([
            'class' => "fi-ta-action inline-flex items-center justify-center p-1 rounded transition-colors {$colorClasses}",
        ]) }}>
        @if ($icon)
            <span class="h-5 w-5 shrink-0">{!! $icon !!}</span>
        @endif
    </button>
@endif
