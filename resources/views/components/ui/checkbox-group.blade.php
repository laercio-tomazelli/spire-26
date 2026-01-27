@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'value' => [],
    'disabled' => false,
    'required' => false,
    'inline' => false,
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, error
    'description' => null,
])

@php
    // Ensure value is an array
    $selectedValues = is_array($value) ? $value : (empty($value) ? [] : [$value]);

    $groupClasses = $inline ? 'flex flex-wrap gap-6' : 'space-y-3';
@endphp

<div class="w-full" data-checkbox-group>
    {{-- Group Label --}}
    @if ($label)
        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif

    {{-- Description --}}
    @if ($description)
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
            {{ $description }}
        </p>
    @endif

    {{-- Checkboxes --}}
    <div class="{{ $groupClasses }}" role="group" @if ($label) aria-labelledby="checkbox-group-label" @endif>
        @if (!empty($options))
            {{-- Array of options --}}
            @foreach ($options as $optionValue => $optionLabel)
                <x-spire::checkbox
                    :name="$name ? $name . '[]' : null"
                    :value="$optionValue"
                    :label="$optionLabel"
                    :checked="in_array($optionValue, $selectedValues)"
                    :disabled="$disabled"
                    :size="$size"
                    :variant="$variant"
                />
            @endforeach
        @else
            {{-- Slot-based checkboxes --}}
            {{ $slot }}
        @endif
    </div>

    {{-- Hidden input for empty array when required --}}
    @if ($required && empty($options))
        <input type="hidden" name="{{ $name }}" value="" />
    @endif
</div>