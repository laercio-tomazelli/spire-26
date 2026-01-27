@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'value' => null,
    'disabled' => false,
    'required' => false,
    'inline' => false,
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, error
    'description' => null,
    'error' => null,
])

@php
    $groupId = $name ?? 'radio-group-' . uniqid();
    $containerClasses = $inline ? 'flex flex-wrap gap-4' : 'space-y-3';

    if ($error) {
        $variant = 'error';
    }
@endphp

<fieldset class="w-full" data-radio-group>
    {{-- Group Label --}}
    @if ($label)
        <legend class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </legend>
    @endif

    {{-- Description --}}
    @if ($description)
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">{{ $description }}</p>
    @endif

    {{-- Radio Options Container --}}
    <div class="{{ $containerClasses }}">
        @if (count($options) > 0)
            {{-- Render from options array --}}
            @foreach ($options as $optionValue => $optionLabel)
                @php
                    $isChecked = $value !== null && (string) $value === (string) $optionValue;
                    $optionDescription = null;

                    // Handle options with description
                    if (is_array($optionLabel)) {
                        $optionDescription = $optionLabel['description'] ?? null;
                        $optionLabel = $optionLabel['label'] ?? $optionLabel['name'] ?? $optionValue;
                    }
                @endphp
                <x-spire::radio
                    :name="$name"
                    :value="$optionValue"
                    :label="$optionLabel"
                    :checked="$isChecked"
                    :disabled="$disabled"
                    :required="$required && $loop->first"
                    :size="$size"
                    :variant="$variant"
                    :description="$optionDescription"
                />
            @endforeach
        @else
            {{-- Render slot content --}}
            {{ $slot }}
        @endif
    </div>

    {{-- Error Message --}}
    @if ($error)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</fieldset>
