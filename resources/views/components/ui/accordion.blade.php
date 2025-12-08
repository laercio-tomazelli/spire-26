@props([
    'id' => null,
    'multiple' => true
])

<div 
    {{ $attributes->merge(['class' => 'w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden']) }} 
    data-v="accordion"
    @if($id) id="{{ $id }}" @endif
    data-multiple="{{ $multiple ? 'true' : 'false' }}"
>
    {{ $slot }}
</div>
