@props([
    'id' => null,
    'position' => 'bottom',
    'trigger' => 'Click me'
])

<div 
    {{ $attributes->merge(['class' => 'relative inline-block']) }}
    data-v="popover"
    data-position="{{ $position }}"
    @if($id) id="{{ $id }}" @endif
>
    {{-- Trigger --}}
    <div data-popover-trigger class="cursor-pointer">
        @if(isset($triggerSlot))
            {{ $triggerSlot }}
        @else
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                {{ $trigger }}
            </button>
        @endif
    </div>
    
    {{-- Content --}}
    <div 
        data-popover-content
        class="hidden absolute z-50 w-64 p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl opacity-0 scale-95 transition-all duration-150"
        role="dialog"
    >
        @if(isset($title))
            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $title }}</h4>
        @endif
        <div class="text-sm text-gray-600 dark:text-gray-300">
            {{ $slot }}
        </div>
    </div>
</div>
