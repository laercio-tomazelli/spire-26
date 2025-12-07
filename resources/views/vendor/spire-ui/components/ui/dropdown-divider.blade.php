@props([
    'label' => null,
])

@if($label)
    {{-- Header --}}
    <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
        {{ $label }}
    </div>
@else
    {{-- Divider --}}
    <div class="my-1 border-t border-gray-200 dark:border-gray-700"></div>
@endif
