@props(['name', 'label', 'checked' => true, 'disabled' => false])

<label class="fi-ta-col-manager-item flex cursor-pointer items-center gap-x-3">
    <input type="checkbox" name="columns[]" value="{{ $name }}" @checked($checked)
        @disabled($disabled)
        class="fi-checkbox-input h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-primary-600"
        x-on:change="$dispatch('table-toggle-column', { name: '{{ $name }}', visible: $event.target.checked })" />

    <span class="text-sm text-gray-700 dark:text-gray-300 {{ $disabled ? 'opacity-50' : '' }}">
        {{ $label }}
    </span>
</label>
