@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'value' => null,
    'checked' => false,
    'disabled' => false,
    'required' => false,
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, error
    'description' => null,
])

@php
    $radioId = $id ?? ($name ? $name . '-' . ($value ?? uniqid()) : 'radio-' . uniqid());

    $sizeClasses = match ($size) {
        'sm' => 'w-3 h-3',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4',
    };

    $labelSizeClasses = match ($size) {
        'sm' => 'text-sm',
        'lg' => 'text-base',
        default => 'text-sm',
    };

    $stateClasses = '';
    if ($disabled) {
        $stateClasses = 'opacity-60 cursor-not-allowed';
    } elseif ($variant === 'error') {
        $stateClasses = 'text-red-600';
    }

    $radioClasses = $sizeClasses . ' text-blue-600 border-gray-300 focus:ring-blue-500 focus:ring-2';
    if ($variant === 'error') {
        $radioClasses .= ' border-red-500 focus:ring-red-500';
    }
@endphp

<div class="flex items-start space-x-3 {{ $stateClasses }}" data-radio-container>
    {{-- Radio Input --}}
    <div class="relative flex items-center flex-shrink-0">
        <input
            type="radio"
            id="{{ $radioId }}"
            @if ($name) name="{{ $name }}" @endif
            @if ($value !== null) value="{{ $value }}" @endif
            @checked($checked)
            @disabled($disabled)
            @required($required)
            data-v="radio"
            class="{{ $radioClasses }}"
            {{ $attributes->except(['class', 'id', 'name', 'value', 'checked', 'disabled', 'required']) }}
        />
    </div>

    {{-- Label and Description --}}
    @if ($label)
        <div class="flex-1 min-w-0">
            <label for="{{ $radioId }}" class="block {{ $labelSizeClasses }} font-medium text-gray-900 dark:text-gray-100 cursor-pointer select-none {{ $disabled ? 'cursor-not-allowed' : '' }}">
                <span class="inline-block">
                    {{ $label }}
                    @if ($required)
                        <span class="text-red-500 ml-1">*</span>
                    @endif
                </span>
            </label>

            @if ($description)
                <p class="mt-1 {{ $labelSizeClasses }} text-gray-500 dark:text-gray-400">
                    {{ $description }}
                </p>
            @endif
        </div>
    @endif
</div>
