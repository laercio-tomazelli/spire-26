@props([
    'id' => null,
    'name' => null,
    'startPlaceholder' => 'Data início',
    'endPlaceholder' => 'Data fim',
    'startValue' => null,
    'endValue' => null,
    'min' => null,
    'max' => null,
    'format' => 'dd/mm/yyyy',
    'label' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $pickerId = $id ?? 'daterange-' . uniqid();
@endphp

<div class="w-full">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div 
        data-v="daterangepicker"
        id="{{ $pickerId }}"
        data-format="{{ $format }}"
        data-start-placeholder="{{ $startPlaceholder }}"
        data-end-placeholder="{{ $endPlaceholder }}"
        @if($startValue) data-start-value="{{ $startValue }}" @endif
        @if($endValue) data-end-value="{{ $endValue }}" @endif
        @if($min) data-min="{{ $min }}" @endif
        @if($max) data-max="{{ $max }}" @endif
        {{ $attributes->merge(['class' => $disabled ? 'opacity-50 pointer-events-none' : '']) }}
    ></div>
    
    @if($hint)
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
