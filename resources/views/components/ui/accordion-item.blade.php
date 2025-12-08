@props([
    'name',
    'title',
    'open' => false
])

<div data-accordion-item="{{ $name }}" {{ $attributes }}>
    <button 
        data-accordion-trigger
        type="button"
        aria-expanded="{{ $open ? 'true' : 'false' }}"
        class="flex items-center justify-between w-full px-5 py-4 text-left font-medium text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
    >
        <span>{{ $title }}</span>
        <svg 
            class="w-5 h-5 transition-transform duration-200" 
            :class="{ 'rotate-180': open }"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div 
        data-accordion-content 
        class="{{ $open ? '' : 'hidden' }} px-5 pb-4 text-gray-600 dark:text-gray-300"
    >
        {{ $slot }}
    </div>
</div>
