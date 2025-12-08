@props([
    'id' => 'stepper-' . uniqid(),
    'steps' => [],
    'initialStep' => 1,
    'linear' => true
])

<div 
    {{ $attributes->merge(['class' => 'w-full']) }}
    data-v="stepper"
    data-linear="{{ $linear ? 'true' : 'false' }}"
    data-initial-step="{{ $initialStep }}"
    id="{{ $id }}"
    role="navigation"
    aria-label="Progresso"
>
    {{-- Steps Header --}}
    <div class="flex items-center justify-between mb-8">
        @foreach($steps as $index => $step)
            @php
                $stepNum = $index + 1;
                $isActive = $stepNum === $initialStep;
                $isCompleted = $stepNum < $initialStep;
                $isLast = $stepNum === count($steps);
            @endphp
            
            {{-- Step --}}
            <div 
                data-step="{{ $stepNum }}"
                class="flex items-center {{ $isActive ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }} group cursor-pointer"
                role="tab"
                aria-selected="{{ $isActive ? 'true' : 'false' }}"
            >
                {{-- Step Circle --}}
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300
                    {{ $isCompleted ? 'bg-green-600 border-green-600 text-white' : '' }}
                    {{ $isActive ? 'bg-blue-600 border-blue-600 text-white' : '' }}
                    {{ !$isActive && !$isCompleted ? 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 group-hover:border-blue-400' : '' }}
                ">
                    <span data-step-indicator class="text-sm font-semibold">
                        @if($isCompleted)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $stepNum }}
                        @endif
                    </span>
                </div>
                
                {{-- Step Label --}}
                <div class="ml-3 hidden sm:block">
                    <p class="text-sm font-medium {{ $isActive ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white' }}">
                        {{ $step['title'] ?? "Etapa $stepNum" }}
                    </p>
                    @if(isset($step['description']))
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $step['description'] }}</p>
                    @endif
                </div>
            </div>
            
            {{-- Connector Line --}}
            @if(!$isLast)
                <div class="flex-1 h-0.5 mx-4 bg-gray-200 dark:bg-gray-700 transition-colors {{ $isCompleted ? 'bg-green-600' : '' }}"></div>
            @endif
        @endforeach
    </div>
    
    {{-- Step Panels --}}
    <div class="mt-6">
        @foreach($steps as $index => $step)
            @php
                $stepNum = $index + 1;
                $isActive = $stepNum === $initialStep;
            @endphp
            
            <div 
                data-step-panel="{{ $stepNum }}"
                class="{{ $isActive ? '' : 'hidden' }}"
                role="tabpanel"
                aria-hidden="{{ $isActive ? 'false' : 'true' }}"
            >
                @if(isset($step['content']))
                    {{ $step['content'] }}
                @endif
            </div>
        @endforeach
        
        {{ $slot }}
    </div>
    
    {{-- Navigation Buttons --}}
    @if(isset($navigation) && $navigation)
        <div class="flex justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button 
                type="button"
                data-step-prev
                class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Anterior
                </span>
            </button>
            
            <button 
                type="button"
                data-step-next
                class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition-opacity"
            >
                <span class="flex items-center gap-2">
                    Próximo
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </button>
        </div>
    @endif
</div>
