@props([
    'type' => 'text',
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

    // Icons
    'icon' => null, // Left icon SVG
    'iconRight' => null, // Right icon SVG

    // Addons
    'prefix' => null, // Prefix text (ex: www)
    'suffix' => null, // Suffix text (ex: .com)

    // Features
    'clearable' => false, // Show clear button
    'inline' => false, // Inline/floating label style

    // Password specific
    'password' => false, // Password mode with toggle
    'passwordToggle' => 'left', // left, right, none

    // Money specific
    'money' => false, // Money input mode
    'currency' => 'USD', // Currency symbol
    'locale' => 'en-US', // Locale for formatting

    // Help popover
    'help' => null, // Help text
    'helpIcon' => 'question', // question, info

    // Sizing
    'size' => 'md', // sm, md, lg
])

@php
    $inputId = $id ?? ($name ?? 'input-' . uniqid());
    $inputType = $password ? 'password' : $type;

    $sizeClasses = match ($size) {
        'sm' => 'h-8 px-3 text-sm',
        'lg' => 'h-11 px-5 text-base',
        default => 'h-9 px-4 text-sm',
    };

    $baseClasses =
        'w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all';

    $stateClasses = '';
    if ($disabled) {
        $stateClasses = 'bg-gray-100 dark:bg-gray-700 text-gray-500 cursor-not-allowed opacity-60';
    } elseif ($readonly) {
        $stateClasses = 'bg-transparent border-dashed cursor-default';
    } elseif ($error) {
        $stateClasses = 'border-red-500 focus:ring-red-500 focus:border-red-500';
    }

    // Calculate padding based on content
    $hasLeftIcon = $icon || ($password && $passwordToggle === 'left');
    $hasRightIcon = $iconRight || $clearable || ($password && $passwordToggle === 'right');

    $paddingClasses = [];
    if ($hasLeftIcon) {
        $paddingClasses[] = 'pl-10';
    }
    if ($prefix) {
        $paddingClasses[] = 'pl-16';
    }
    if ($hasRightIcon) {
        $paddingClasses[] = 'pr-10';
    }
    if ($suffix) {
        $paddingClasses[] = 'pr-16';
    }
    if (isset($prepend)) {
        $paddingClasses[] = 'rounded-l-none';
    }
    if (isset($append)) {
        $paddingClasses[] = 'rounded-r-none';
    }

    $moneyConfig = $money ? json_encode(['currency' => $currency, 'locale' => $locale]) : null;
@endphp

<div class="w-full" data-input-container
    @if ($money) data-money-config="{{ $moneyConfig }}" @endif>
    {{-- Label --}}
    @if ($label && !$inline)
        <label for="{{ $inputId }}"
            class="flex items-center gap-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
            @if ($help)
                <span class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-help"
                    title="{{ $help }}">
                    @if ($helpIcon === 'info')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </span>
            @endif
        </label>
    @endif

    {{-- Input Container --}}
    <div class="relative flex">
        {{-- Prepend Slot --}}
        @if (isset($prepend))
            <div
                class="flex items-center border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-lg bg-gray-50 dark:bg-gray-700 px-3 text-gray-600 dark:text-gray-300">
                {{ $prepend }}
            </div>
        @endif

        {{-- Input Wrapper --}}
        <div class="relative flex-1">
            {{-- Inline/Floating Label --}}
            @if ($label && $inline)
                <label for="{{ $inputId }}"
                    class="absolute left-3 -top-2.5 px-1 text-xs font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 z-10">
                    {{ $label }}
                    @if ($required)
                        <span class="text-red-500">*</span>
                    @endif
                </label>
            @endif

            {{-- Left Content --}}
            @if ($hasLeftIcon || $prefix)
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 gap-2">
                    {{-- Password Toggle Left --}}
                    @if ($password && $passwordToggle === 'left')
                        <button type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                            onclick="
                                const input = this.closest('[data-input-container]').querySelector('input');
                                const isPassword = input.type === 'password';
                                input.type = isPassword ? 'text' : 'password';
                                this.querySelector('.icon-hidden').classList.toggle('hidden', !isPassword);
                                this.querySelector('.icon-visible').classList.toggle('hidden', isPassword);
                            ">
                            <svg class="w-5 h-5 icon-hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <svg class="w-5 h-5 icon-visible hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    @elseif($icon)
                        <span class="text-gray-400">{!! $icon !!}</span>
                    @endif

                    {{-- Prefix --}}
                    @if ($prefix)
                        <span
                            class="text-gray-500 dark:text-gray-400 text-sm border-r border-gray-300 dark:border-gray-600 pr-3">{{ $prefix }}</span>
                    @endif
                </div>
            @endif

            {{-- The Input --}}
            <input type="{{ $inputType }}" id="{{ $inputId }}"
                @if ($name) name="{{ $name }}" @endif
                @if ($placeholder) placeholder="{{ $placeholder }}" @endif
                @if ($value) value="{{ $value }}" @endif @disabled($disabled)
                @readonly($readonly) @required($required) data-v="input" @if ($money) data-money @endif
                @if ($clearable) oninput="this.closest('[data-input-container]').querySelector('[data-clear-btn]')?.classList.toggle('hidden', !this.value)" @endif
                {{ $attributes->merge([
                    'class' => implode(' ', array_filter([$baseClasses, $sizeClasses, $stateClasses, ...$paddingClasses])),
                ]) }} />

            {{-- Right Content --}}
            @if ($hasRightIcon || $suffix)
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 gap-2">
                    {{-- Suffix --}}
                    @if ($suffix)
                        <span
                            class="text-gray-500 dark:text-gray-400 text-sm border-l border-gray-300 dark:border-gray-600 pl-3">{{ $suffix }}</span>
                    @endif

                    {{-- Clearable Button --}}
                    @if ($clearable)
                        <button type="button" data-clear-btn
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors hidden"
                            onclick="
                                const input = this.closest('[data-input-container]').querySelector('input');
                                input.value = '';
                                input.focus();
                                this.classList.add('hidden');
                            ">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif

                    {{-- Password Toggle Right --}}
                    @if ($password && $passwordToggle === 'right')
                        <button type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                            onclick="
                                const input = this.closest('[data-input-container]').querySelector('input');
                                const isPassword = input.type === 'password';
                                input.type = isPassword ? 'text' : 'password';
                                this.querySelector('.icon-hidden').classList.toggle('hidden', !isPassword);
                                this.querySelector('.icon-visible').classList.toggle('hidden', isPassword);
                            ">
                            <svg class="w-5 h-5 icon-hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <svg class="w-5 h-5 icon-visible hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    @endif

                    {{-- Right Icon --}}
                    @if ($iconRight && !$suffix && !$clearable && !($password && $passwordToggle === 'right'))
                        <span class="text-gray-400">{!! $iconRight !!}</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Append Slot --}}
        @if (isset($append))
            <div class="flex items-center">
                {{ $append }}
            </div>
        @endif
    </div>

    {{-- Hint / Error --}}
    @if ($hint && !$error)
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="mt-1.5 text-sm text-red-500 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $error }}
        </p>
    @endif
</div>
