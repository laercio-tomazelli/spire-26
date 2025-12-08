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
    $selectedOption = $value && count($options) ? collect($options)->firstWhere('value', $value) : null;
    $initialLabel = $selectedOption['label'] ?? $placeholder;
    $initialIcon = $selectedOption['icon'] ?? null;
    $initialColor = $selectedOption['color'] ?? '';
    $optionsJson = json_encode($options, JSON_HEX_APOS | JSON_HEX_QUOT);

    $sizeClasses = match ($size) {
        'sm' => 'h-8 px-3 text-sm',
        'lg' => 'h-11 px-5 text-base',
        default => 'h-9 px-4 text-sm',
    };
@endphp

<div class="w-full" x-data="{
    open: false,
    value: {{ json_encode($value) }},
    label: {{ json_encode($initialLabel) }},
    icon: {{ json_encode($initialIcon) }},
    color: {{ json_encode($initialColor) }},
    placeholder: {{ json_encode($placeholder) }},
    options: {{ $optionsJson }},

    select(option) {
        this.value = option.value;
        this.label = option.label;
        this.icon = option.icon || null;
        this.color = option.color || '';
        this.open = false;
        if (this.$refs.input) {
            this.$refs.input.value = option.value;
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        }
        this.$dispatch('select-change', { value: option.value, name: {{ json_encode($name) }} });
    },

    reset() {
        this.value = '';
        this.label = this.placeholder;
        this.icon = null;
        this.color = '';
        if (this.$refs.input) {
            this.$refs.input.value = '';
        }
    }
}" @click.away="open = false"
    @select-reset.window="if ($event.detail.name === {{ json_encode($name) }} || $event.detail.name === '*') reset()"
    {{ $attributes->whereDoesntStartWith('x-model')->merge(['class' => '']) }}>
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if ($name)
            <input type="hidden" name="{{ $name }}" x-ref="input" :value="value"
                value="{{ $value }}">
        @endif

        {{-- Trigger --}}
        <button type="button" @click="open = !open"
            class="w-full flex items-center justify-between {{ $sizeClasses }} text-left bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <span class="flex items-center gap-2" :class="color">
                <span x-show="icon" x-html="icon" class="w-4 h-4 shrink-0"></span>
                <span x-text="label || placeholder"></span>
            </span>
            <svg class="w-5 h-5 text-gray-400 transition-transform shrink-0" :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Dropdown --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden"
            x-cloak>
            <div class="max-h-60 overflow-y-auto">
                <template x-for="option in options" :key="option.value">
                    <div @click="if (!option.disabled) select(option)"
                        :class="{
                            'bg-blue-50 dark:bg-blue-900/30': value === option.value,
                            'opacity-50 cursor-not-allowed': option.disabled,
                            'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700': !option.disabled,
                            [option.color || 'text-gray-700 dark:text-gray-200']: true
                        }"
                        class="flex items-center gap-2 px-4 py-2.5 transition-colors">
                        <span x-show="option.icon" x-html="option.icon" class="w-4 h-4 shrink-0"></span>
                        <span x-text="option.label"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
