@props([
    'id' => null,
    'name' => null,
    'value' => 0,
    'max' => 5,
    'size' => 'md', // sm, md, lg, xl
    'color' => 'yellow', // yellow, red, blue, green, purple
    'readonly' => false,
    'disabled' => false,
    'half' => false, // permitir meio estrela
    'icon' => 'star', // star, heart, thumb
    'showValue' => false, // mostrar valor numérico
    'showCount' => null, // ex: "(123 avaliações)"
])

@php
    $ratingId = $id ?? 'rating-' . uniqid();
    
    $sizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-6 h-6',
        'lg' => 'w-8 h-8',
        'xl' => 'w-10 h-10',
    ];

    $colors = [
        'yellow' => 'text-yellow-400',
        'red' => 'text-red-500',
        'blue' => 'text-blue-500',
        'green' => 'text-green-500',
        'purple' => 'text-purple-500',
    ];

    $icons = [
        'star' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
        'heart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
        'thumb' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $colorClass = $colors[$color] ?? $colors['yellow'];
    $iconPath = $icons[$icon] ?? $icons['star'];
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';
    $cursorClass = $readonly || $disabled ? '' : 'cursor-pointer';
@endphp

<div 
    data-rating="{{ $ratingId }}"
    data-value="{{ $value }}"
    data-max="{{ $max }}"
    data-half="{{ $half ? 'true' : 'false' }}"
    data-readonly="{{ $readonly ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => "inline-flex items-center gap-1 {$disabledClass}"]) }}
>
    @if($name)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" data-rating-input>
    @endif

    <div class="inline-flex items-center {{ $cursorClass }}" data-rating-stars>
        @for($i = 1; $i <= $max; $i++)
            <button
                type="button"
                class="relative focus:outline-none transition-transform {{ !$readonly && !$disabled ? 'hover:scale-110' : '' }}"
                data-rating-star="{{ $i }}"
                @if($disabled) disabled @endif
                @if($readonly) tabindex="-1" @endif
            >
                {{-- Empty star (background) --}}
                <svg class="{{ $sizeClass }} text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $iconPath !!}
                </svg>
                
                {{-- Filled star (overlay) --}}
                <svg 
                    class="absolute inset-0 {{ $sizeClass }} {{ $colorClass }} transition-all"
                    fill="currentColor" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                    data-rating-fill
                    style="clip-path: inset(0 {{ $i <= $value ? '0%' : ($half && $i - 0.5 <= $value ? '50%' : '100%') }} 0 0);"
                >
                    {!! $iconPath !!}
                </svg>
            </button>
        @endfor
    </div>

    @if($showValue)
        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300" data-rating-value-display>
            {{ number_format($value, $half ? 1 : 0) }}
        </span>
    @endif

    @if($showCount)
        <span class="ml-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $showCount }}
        </span>
    @endif
</div>
