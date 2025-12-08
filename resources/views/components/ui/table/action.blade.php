@props([
    'href' => null,
    'icon' => null,
    'color' => 'primary', // primary, danger, warning, success, info
])

@php
    $colorClasses = match($color) {
        'danger' => 'text-danger-600 hover:text-danger-500 dark:text-danger-400 dark:hover:text-danger-300',
        'warning' => 'text-warning-600 hover:text-warning-500 dark:text-warning-400 dark:hover:text-warning-300',
        'success' => 'text-success-600 hover:text-success-500 dark:text-success-400 dark:hover:text-success-300',
        'info' => 'text-info-600 hover:text-info-500 dark:text-info-400 dark:hover:text-info-300',
        default => 'text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300',
    };
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge([
            'class' => "fi-ta-action inline-flex items-center gap-x-1 text-sm font-medium {$colorClasses}",
        ]) }}
    >
        @if($icon)
            <span class="h-4 w-4 shrink-0">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="button"
        {{ $attributes->merge([
            'class' => "fi-ta-action inline-flex items-center gap-x-1 text-sm font-medium {$colorClasses}",
        ]) }}
    >
        @if($icon)
            <span class="h-4 w-4 shrink-0">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </button>
@endif
