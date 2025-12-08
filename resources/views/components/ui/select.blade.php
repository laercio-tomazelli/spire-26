@props([
    'id' => null,
    'name' => null,
    'placeholder' => 'Selecione...',
    'value' => '',
    'options' => []
])

<div 
    {{ $attributes->merge(['class' => 'relative w-full']) }} 
    data-v="select"
    @if($id) id="{{ $id }}" @endif
>
    @if($name)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endif
    
    {{-- Trigger --}}
    <button 
        type="button"
        data-select-trigger
        class="w-full flex items-center justify-between px-4 py-3 text-left bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
    >
        <span data-select-value class="text-gray-700 dark:text-gray-200">
            @if($value && count($options))
                {{ collect($options)->firstWhere('value', $value)['label'] ?? $placeholder }}
            @else
                {{ $placeholder }}
            @endif
        </span>
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    
    {{-- Dropdown --}}
    <div 
        data-select-dropdown
        class="hidden absolute z-10 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden"
    >
        <div data-select-options class="max-h-60 overflow-y-auto">
            @foreach($options as $option)
                <div 
                    data-option="{{ $option['value'] }}"
                    class="px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors {{ $value === $option['value'] ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }} {{ isset($option['disabled']) && $option['disabled'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                    @if(isset($option['disabled']) && $option['disabled']) disabled @endif
                >
                    {{ $option['label'] }}
                </div>
            @endforeach
        </div>
    </div>
</div>
