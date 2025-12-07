@props([
    'id' => null,
    'value' => 0,
    'max' => 100,
    'showLabel' => true,
    'size' => 'md',
    'color' => 'blue',
    'striped' => false,
    'animated' => false
])

@php
    $sizeClasses = [
        'sm' => 'h-1.5',
        'md' => 'h-2.5',
        'lg' => 'h-4',
        'xl' => 'h-6',
    ];
    
    $colorClasses = [
        'blue' => 'bg-blue-600',
        'green' => 'bg-green-600',
        'red' => 'bg-red-600',
        'yellow' => 'bg-yellow-500',
        'purple' => 'bg-purple-600',
        'pink' => 'bg-pink-600',
        'gradient' => 'bg-gradient-to-r from-blue-600 to-purple-600',
    ];
    
    $percent = min(100, max(0, ($value / $max) * 100));
@endphp

<div 
    {{ $attributes->merge(['class' => 'w-full']) }}
    data-v="progress"
    data-value="{{ $value }}"
    data-max="{{ $max }}"
    @if($id) id="{{ $id }}" @endif
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="{{ $max }}"
    aria-valuenow="{{ $value }}"
>
    @if($showLabel)
        <div class="flex justify-between items-center mb-1">
            @if(isset($label))
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $label }}</span>
            @endif
            <span data-progress-label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ round($percent) }}%</span>
        </div>
    @endif
    
    <div class="w-full {{ $sizeClasses[$size] }} bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
        <div 
            data-progress-bar
            class="{{ $colorClasses[$color] }} {{ $sizeClasses[$size] }} rounded-full transition-all duration-300 ease-out {{ $striped ? 'bg-stripes' : '' }} {{ $animated ? 'animate-progress-stripes' : '' }}"
            style="width: {{ $percent }}%"
        ></div>
    </div>
</div>

@once
    @push('styles')
    <style>
        .bg-stripes {
            background-image: linear-gradient(
                45deg,
                rgba(255,255,255,0.15) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255,255,255,0.15) 50%,
                rgba(255,255,255,0.15) 75%,
                transparent 75%,
                transparent
            );
            background-size: 1rem 1rem;
        }
        
        @keyframes progress-stripes {
            from { background-position: 1rem 0; }
            to { background-position: 0 0; }
        }
        
        .animate-progress-stripes {
            animation: progress-stripes 1s linear infinite;
        }
    </style>
    @endpush
@endonce
