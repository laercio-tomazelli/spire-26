@props([
    'name',
    'active' => false,
    'disabled' => false,
    'variant' => 'underline'
])

@php
    $baseClasses = 'text-sm font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap';
    
    $variantClasses = [
        'underline' => 'px-4 py-3 border-b-2 -mb-px border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 [&.active]:border-blue-600 [&.active]:text-blue-600 dark:[&.active]:border-blue-400 dark:[&.active]:text-blue-400',
        
        'pills' => 'px-4 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-white/50 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700/50 [&.active]:bg-white dark:[&.active]:bg-gray-700 [&.active]:text-gray-900 dark:[&.active]:text-white [&.active]:shadow-sm',
        
        'boxed' => 'px-4 py-3 text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50 border-r border-gray-200 dark:border-gray-700 last:border-r-0 [&.active]:bg-blue-50 dark:[&.active]:bg-blue-900/30 [&.active]:text-blue-600 dark:[&.active]:text-blue-400',
        
        'minimal' => 'px-4 py-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 [&.active]:bg-blue-100 dark:[&.active]:bg-blue-900/40 [&.active]:text-blue-700 dark:[&.active]:text-blue-300',
        
        'segmented' => 'px-4 py-1.5 rounded-md text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white [&.active]:bg-white dark:[&.active]:bg-gray-700 [&.active]:text-gray-900 dark:[&.active]:text-white [&.active]:shadow-sm',
    ];
    
    $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['underline']);
@endphp

<button 
    data-tab="{{ $name }}"
    role="tab"
    aria-selected="{{ $active ? 'true' : 'false' }}"
    aria-disabled="{{ $disabled ? 'true' : 'false' }}"
    tabindex="{{ $active ? '0' : '-1' }}"
    @if($disabled) disabled @endif
    {{ $attributes->merge([
        'class' => $classes . ($active ? ' active' : '') . ($disabled ? ' disabled' : '')
    ]) }}
>
    {{ $slot }}
</button>
