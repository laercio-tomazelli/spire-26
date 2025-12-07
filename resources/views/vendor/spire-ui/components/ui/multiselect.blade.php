@props([
    'id' => null,
    'name' => null,
    'placeholder' => 'Selecione...',
    'value' => [],
    'options' => [],
    'searchable' => true,
    'maxItems' => 0,
    'showSelectAll' => true,
    'showClear' => true
])

@php
    $initialValue = is_array($value) ? $value : (is_string($value) ? json_decode($value, true) ?? [] : []);
@endphp

<div 
    {{ $attributes->merge(['class' => 'relative w-full']) }} 
    data-v="multiselect"
    @if($id) id="{{ $id }}" @endif
    @if($searchable) data-searchable="true" @endif
    @if($maxItems > 0) data-max-items="{{ $maxItems }}" @endif
    data-placeholder="{{ $placeholder }}"
>
    @if($name)
        <input type="hidden" name="{{ $name }}" value="{{ json_encode($initialValue) }}">
    @endif
    
    {{-- Trigger with Tags --}}
    <div 
        data-multiselect-trigger
        class="w-full flex flex-wrap items-center gap-2 min-h-[48px] px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm hover:border-gray-400 dark:hover:border-gray-500 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 cursor-pointer transition-colors"
        role="combobox"
        aria-haspopup="listbox"
        aria-expanded="false"
        aria-multiselectable="true"
    >
        <div data-multiselect-tags class="flex flex-wrap gap-1.5 flex-1">
            @if(count($initialValue) === 0)
                <span class="text-gray-500 dark:text-gray-400">{{ $placeholder }}</span>
            @else
                @foreach($initialValue as $val)
                    @php
                        $opt = collect($options)->firstWhere('value', $val);
                    @endphp
                    @if($opt)
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-sm bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg">
                            {{ $opt['label'] }}
                            <button type="button" data-remove-tag="{{ $val }}" class="hover:text-blue-900 dark:hover:text-blue-100 transition-colors" aria-label="Remover {{ $opt['label'] }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    @endif
                @endforeach
            @endif
        </div>
        
        {{-- Dropdown Arrow --}}
        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
    
    {{-- Dropdown --}}
    <div 
        data-multiselect-dropdown
        class="hidden absolute z-20 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden"
    >
        {{-- Search Input --}}
        @if($searchable)
            <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                <input 
                    type="text" 
                    data-multiselect-search
                    placeholder="Buscar..."
                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
            </div>
        @endif
        
        {{-- Actions (Select All / Clear) --}}
        @if($showSelectAll || $showClear)
            <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <span data-multiselect-count class="text-sm text-gray-500 dark:text-gray-400">
                    {{ count($initialValue) }} selecionado{{ count($initialValue) !== 1 ? 's' : '' }}
                </span>
                <div class="flex gap-2">
                    @if($showSelectAll)
                        <button 
                            type="button" 
                            data-select-all 
                            class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
                        >
                            Selecionar todos
                        </button>
                    @endif
                    @if($showSelectAll && $showClear)
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                    @endif
                    @if($showClear)
                        <button 
                            type="button" 
                            data-clear-all 
                            class="text-sm text-red-600 dark:text-red-400 hover:underline"
                        >
                            Limpar
                        </button>
                    @endif
                </div>
            </div>
        @endif
        
        {{-- Options List --}}
        <div data-multiselect-options class="max-h-60 overflow-y-auto" role="listbox" aria-multiselectable="true">
            @foreach($options as $index => $option)
                @php
                    $isSelected = in_array($option['value'], $initialValue);
                    $isDisabled = isset($option['disabled']) && $option['disabled'];
                @endphp
                <div 
                    data-option="{{ $option['value'] }}"
                    role="option"
                    id="multiselect-option-{{ $index }}"
                    aria-selected="{{ $isSelected ? 'true' : 'false' }}"
                    class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 transition-colors {{ $isSelected ? 'selected bg-blue-50 dark:bg-blue-900/30' : '' }} {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                    @if($isDisabled) disabled @endif
                >
                    <input 
                        type="checkbox" 
                        class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 pointer-events-none"
                        @if($isSelected) checked @endif
                        @if($isDisabled) disabled @endif
                        tabindex="-1"
                    >
                    <span class="flex-1">{{ $option['label'] }}</span>
                    @if(isset($option['description']))
                        <span class="text-sm text-gray-400 dark:text-gray-500">{{ $option['description'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
        
        {{-- Footer with max items info --}}
        @if($maxItems > 0)
            <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    Máximo de {{ $maxItems }} {{ $maxItems === 1 ? 'item' : 'itens' }}
                </span>
            </div>
        @endif
    </div>
</div>
