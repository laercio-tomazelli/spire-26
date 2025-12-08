@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'placeholder' => 'Selecione...',
    'value' => '',
    'options' => [],
    'size' => 'md',
])

@php
    $selectId = $id ?? ($name ? $name . '-select' : 'select-' . uniqid());
    $selectedOption = $value && count($options) ? collect($options)->firstWhere('value', (string) $value) : null;
    $initialLabel = $selectedOption['label'] ?? $placeholder;
    $optionsJson = json_encode($options, JSON_HEX_APOS | JSON_HEX_QUOT);

    $sizeClasses = match ($size) {
        'sm' => 'h-8 px-3 text-sm',
        'lg' => 'h-11 px-5 text-base',
        default => 'h-9 px-4 text-sm',
    };
@endphp

<div class="w-full" data-v="select" data-options="{{ $optionsJson }}" data-name="{{ $name }}"
    data-placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => '']) }}>
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if ($name)
            <input type="hidden" name="{{ $name }}" data-select-input value="{{ $value }}">
        @endif

        {{-- Trigger --}}
        <button type="button" data-select-trigger
            class="w-full flex items-center justify-between {{ $sizeClasses }} text-left bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <span class="flex items-center gap-2" data-select-label>
                <span>{{ $initialLabel }}</span>
            </span>
            <svg class="w-5 h-5 text-gray-400 transition-transform shrink-0" data-select-chevron fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Dropdown --}}
        <div data-select-dropdown
            class="hidden absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden opacity-0 scale-95 transition-all duration-100">
            <div class="max-h-60 overflow-y-auto" data-select-options>
                @foreach ($options as $option)
                    <div data-option="{{ $option['value'] }}"
                        class="flex items-center gap-2 px-4 py-2.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ (string) $value === (string) $option['value'] ? 'bg-blue-50 dark:bg-blue-900/30 selected' : '' }} {{ $option['color'] ?? 'text-gray-700 dark:text-gray-200' }}"
                        {{ $option['disabled'] ?? false ? 'disabled' : '' }}>
                        @if (!empty($option['icon']))
                            <span class="w-4 h-4 shrink-0">{!! $option['icon'] !!}</span>
                        @endif
                        <span>{{ $option['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
