@props([
    'id' => null,
    'default' => null,
    'variant' => 'underline' // underline, pills, boxed, minimal, segmented
])

@php
    $variantClasses = [
        'underline' => 'flex border-b border-gray-200 dark:border-gray-700',
        'pills' => 'flex gap-2 p-1 bg-gray-100 dark:bg-gray-800 rounded-xl',
        'boxed' => 'flex border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden',
        'minimal' => 'flex gap-1',
        'segmented' => 'inline-flex p-1 bg-gray-100 dark:bg-gray-800 rounded-lg',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }} data-v="tabs" data-variant="{{ $variant }}" @if($id) id="{{ $id }}" @endif>
    {{-- Tab List --}}
    <div role="tablist" class="{{ $variantClasses[$variant] ?? $variantClasses['underline'] }} mb-6">
        {{ $tabs }}
    </div>
    
    {{-- Tab Panels --}}
    <div class="tab-panels">
        {{ $slot }}
    </div>
</div>
