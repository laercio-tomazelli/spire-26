@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'hint' => null,
    'placeholder' => null,
    'value' => null,
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'error' => null,
    'rows' => 3,
    'maxlength' => null,
    'size' => 'md', // sm, md, lg
])

@php
    $inputId = $id ?? ($name ?? 'textarea-' . uniqid());

    $sizeClasses = match ($size) {
        'sm' => 'py-2 px-3 text-sm',
        'lg' => 'py-3 px-5 text-base',
        default => 'py-2 px-4 text-sm',
    };

    $baseClasses =
        'w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-y';

    $stateClasses = '';
    if ($disabled) {
        $stateClasses = 'bg-gray-100 dark:bg-gray-700 text-gray-500 cursor-not-allowed opacity-60';
    } elseif ($readonly) {
        $stateClasses = 'bg-transparent border-dashed cursor-default';
    } elseif ($error) {
        $stateClasses = 'border-red-500 dark:border-red-500 focus:ring-red-500 focus:border-red-500';
    }

    $classes = "$baseClasses $sizeClasses $stateClasses";
@endphp

<div class="w-full">
    {{-- Label --}}
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    {{-- Hint --}}
    @if ($hint)
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">{{ $hint }}</p>
    @endif

    {{-- Textarea --}}
    <textarea id="{{ $inputId }}" @if ($name) name="{{ $name }}" @endif
        rows="{{ $rows }}" @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        @if ($disabled) disabled @endif @if ($readonly) readonly @endif
        @if ($required) required @endif {{ $attributes->merge(['class' => $classes]) }}>{{ $value ?? $slot }}</textarea>

    {{-- Error Message --}}
    @if ($error)
        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
