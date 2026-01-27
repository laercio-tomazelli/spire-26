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

    // Textarea specific
    'rows' => 3,
    'maxlength' => null,
    'minlength' => null,
    'resize' => 'vertical', // none, vertical, horizontal, both
    'autoResize' => false,

    // Help popover
    'help' => null,
    'helpIcon' => 'question', // question, info

    // Sizing
    'size' => 'md', // sm, md, lg

    // Variant
    'variant' => 'default', // default, error, success
])

@php
    $textareaId = $id ?? ($name ?? 'textarea-' . uniqid());

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-2 text-sm min-h-8',
        'lg' => 'px-5 py-3 text-base min-h-11',
        default => 'px-4 py-2 text-sm min-h-9',
    };

    $baseClasses =
        'w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-' . $resize;

    $stateClasses = '';
    if ($disabled) {
        $stateClasses = 'bg-gray-100 dark:bg-gray-700 text-gray-500 cursor-not-allowed opacity-60 resize-none';
    } elseif ($readonly) {
        $stateClasses = 'bg-transparent border-dashed cursor-default resize-none';
    } elseif ($variant === 'error' || $error) {
        $stateClasses = 'border-red-500 focus:ring-red-500 focus:border-red-500';
    } elseif ($variant === 'success') {
        $stateClasses = 'border-green-500 focus:ring-green-500 focus:border-green-500';
    }

    $autoResizeScript = $autoResize ? 'data-auto-resize' : '';
@endphp

<div class="w-full" {{ $autoResizeScript }}>
    {{-- Label --}}
    @if ($label)
        <label for="{{ $textareaId }}"
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

    {{-- Textarea --}}
    <textarea id="{{ $textareaId }}"
        @if ($name) name="{{ $name }}" @endif
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($value) value="{{ $value }}" @endif
        @if ($rows) rows="{{ $rows }}" @endif
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        @if ($minlength) minlength="{{ $minlength }}" @endif
        @disabled($disabled)
        @readonly($readonly)
        @required($required)
        data-v="textarea"
        {{ $attributes->merge([
            'class' => $baseClasses . ' ' . $sizeClasses . ' ' . $stateClasses,
        ]) }}>{{ $value }}</textarea>

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

@push('scripts')
@if ($autoResize)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize functionality
    document.querySelectorAll('[data-auto-resize] textarea').forEach(function(textarea) {
        function autoResize() {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        textarea.addEventListener('input', autoResize);
        // Initial resize
        setTimeout(autoResize, 0);
    });
});
</script>
@endif
@endpush