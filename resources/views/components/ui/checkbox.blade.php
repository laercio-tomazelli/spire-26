@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'value' => null,
    'checked' => false,
    'disabled' => false,
    'required' => false,
    'indeterminate' => false,
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, error
    'description' => null,
])

@php
    $checkboxId = $id ?? ($name ?? 'checkbox-' . uniqid());

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
@endphp

<div class="flex items-start space-x-3 {{ $stateClasses }}" data-checkbox-container>
    {{-- Checkbox Input --}}
    <div class="relative flex items-center flex-shrink-0">
        <input
            type="checkbox"
            id="{{ $checkboxId }}"
            @if ($name) name="{{ $name }}" @endif
            @if ($value) value="{{ $value }}" @endif
            @checked($checked)
            @disabled($disabled)
            @required($required)
            data-v="checkbox"
            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 {{ $variant === 'error' ? 'border-red-500 focus:ring-red-500' : '' }}"
            @if ($indeterminate) data-indeterminate @endif
        />
    </div>

    {{-- Label and Description --}}
    @if ($label)
        <div class="flex-1 min-w-0">
            <label for="{{ $checkboxId }}" class="block {{ $labelSizeClasses }} font-medium text-gray-900 dark:text-gray-100 cursor-pointer select-none {{ $disabled ? 'cursor-not-allowed' : '' }}">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle indeterminate state
    document.querySelectorAll('[data-indeterminate]').forEach(function(checkbox) {
        checkbox.indeterminate = true;
    });

    // Handle checkbox state changes
    document.querySelectorAll('[data-v="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Reset indeterminate when user interacts
            if (this.indeterminate) {
                this.indeterminate = false;
                this.removeAttribute('data-indeterminate');
            }
        });
    });

    console.log('Checkbox components initialized');
});
</script>
@endpush