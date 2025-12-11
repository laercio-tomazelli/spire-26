@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'hint' => null,
    'value' => '1',
    'checked' => false,
    'disabled' => false,
    'required' => false,
    'error' => null,
    'size' => 'md', // sm, md, lg
    'color' => 'blue', // blue, green, red, amber, purple
])

@php
    $inputId = $id ?? ($name ?? 'checkbox-' . uniqid());

    $sizeClasses = match ($size) {
        'sm' => 'w-3.5 h-3.5',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4',
    };

    $labelSizeClasses = match ($size) {
        'sm' => 'text-xs',
        'lg' => 'text-base',
        default => 'text-sm',
    };

    $colorClasses = match ($color) {
        'green' => 'text-green-600 focus:ring-green-500',
        'red' => 'text-red-600 focus:ring-red-500',
        'amber' => 'text-amber-600 focus:ring-amber-500',
        'purple' => 'text-purple-600 focus:ring-purple-500',
        default => 'text-blue-600 focus:ring-blue-500',
    };

    $baseClasses = "rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 $sizeClasses $colorClasses";

    $stateClasses = '';
    if ($disabled) {
        $stateClasses = 'opacity-50 cursor-not-allowed';
    }

    $classes = "$baseClasses $stateClasses";
@endphp

<div class="flex items-start gap-2">
    <div class="flex items-center h-5">
        <input type="checkbox" id="{{ $inputId }}"
            @if ($name) name="{{ $name }}" @endif value="{{ $value }}"
            @if ($checked) checked @endif @if ($disabled) disabled @endif
            @if ($required) required @endif {{ $attributes->merge(['class' => $classes]) }}>
    </div>

    @if ($label || $hint)
        <div class="flex flex-col">
            @if ($label)
                <label for="{{ $inputId }}"
                    class="{{ $labelSizeClasses }} font-medium text-gray-700 dark:text-gray-300 {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                    {{ $label }}
                    @if ($required)
                        <span class="text-red-500 ml-0.5">*</span>
                    @endif
                </label>
            @endif

            @if ($hint)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $hint }}</p>
            @endif
        </div>
    @endif

    {{-- Error Message --}}
    @if ($error)
        <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
