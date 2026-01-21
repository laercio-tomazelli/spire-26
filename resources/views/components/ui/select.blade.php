@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'placeholder' => 'Selecione...',
    'hint' => null,
    'options' => [],
    'value' => null,
    'disabled' => false,
    'required' => false,
    'error' => null,
    'size' => 'md',
])

@php
    $selectId = $id ?? 'select-' . uniqid();

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-4 py-3 text-lg',
        default => 'px-4 py-2.5',
    };

    $baseClasses =
        'w-full border rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer';

    $stateClasses = match (true) {
        (bool) $error => 'border-red-500 focus:ring-red-500 focus:border-red-500',
        $disabled => 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed opacity-60 border-gray-300 dark:border-gray-600',
        default => 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
    };
@endphp

<div class="w-full" data-v="select">
    @if ($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select id="{{ $selectId }}" @if ($name) name="{{ $name }}" @endif
            @disabled($disabled) @required($required)
            {{ $attributes->class([$baseClasses, $sizeClasses, $stateClasses]) }}>
            @if ($placeholder)
                <option value="" disabled {{ is_null($value) || $value === '' ? 'selected' : '' }}>
                    {{ $placeholder }}
                </option>
            @endif

            @foreach ($options as $option)
                @php
                    $optionValue = is_array($option) ? $option['value'] ?? ($option['id'] ?? '') : $option;
                    $optionLabel = is_array($option) ? $option['label'] ?? ($option['name'] ?? $optionValue) : $option;
                    $isSelected = (string) $optionValue === (string) $value;
                @endphp
                <option value="{{ $optionValue }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>

        {{-- Dropdown Arrow --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                    clip-rule="evenodd" />
            </svg>
        </div>
    </div>

    @if ($hint && !$error)
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
