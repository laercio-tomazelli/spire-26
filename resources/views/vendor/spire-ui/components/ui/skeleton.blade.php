@props([
    'id' => null,
    'type' => 'line',
    'count' => 1,
    'target' => null
])

@php
    $baseClass = 'animate-pulse bg-gray-200 dark:bg-gray-700 rounded';
@endphp

<div 
    {{ $attributes->merge(['class' => 'space-y-3']) }}
    data-v="skeleton"
    @if($id) id="{{ $id }}" @endif
    @if($target) data-target="{{ $target }}" @endif
>
    @if($type === 'card')
        {{-- Card skeleton --}}
        @for($i = 0; $i < $count; $i++)
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="{{ $baseClass }} h-12 w-12 rounded-full"></div>
                    <div class="flex-1 space-y-2">
                        <div class="{{ $baseClass }} h-4 w-3/4"></div>
                        <div class="{{ $baseClass }} h-3 w-1/2"></div>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="{{ $baseClass }} h-3 w-full"></div>
                    <div class="{{ $baseClass }} h-3 w-5/6"></div>
                    <div class="{{ $baseClass }} h-3 w-4/6"></div>
                </div>
            </div>
        @endfor
    @elseif($type === 'avatar')
        {{-- Avatar skeleton --}}
        <div class="flex items-center space-x-4">
            @for($i = 0; $i < $count; $i++)
                <div class="{{ $baseClass }} h-10 w-10 rounded-full"></div>
            @endfor
        </div>
    @elseif($type === 'table')
        {{-- Table skeleton --}}
        <div class="space-y-3">
            <div class="flex space-x-4">
                <div class="{{ $baseClass }} h-4 w-1/4"></div>
                <div class="{{ $baseClass }} h-4 w-1/4"></div>
                <div class="{{ $baseClass }} h-4 w-1/4"></div>
                <div class="{{ $baseClass }} h-4 w-1/4"></div>
            </div>
            @for($i = 0; $i < $count; $i++)
                <div class="flex space-x-4">
                    <div class="{{ $baseClass }} h-8 w-1/4"></div>
                    <div class="{{ $baseClass }} h-8 w-1/4"></div>
                    <div class="{{ $baseClass }} h-8 w-1/4"></div>
                    <div class="{{ $baseClass }} h-8 w-1/4"></div>
                </div>
            @endfor
        </div>
    @elseif($type === 'image')
        {{-- Image skeleton --}}
        @for($i = 0; $i < $count; $i++)
            <div class="{{ $baseClass }} h-48 w-full rounded-xl"></div>
        @endfor
    @else
        {{-- Line skeleton (default) --}}
        @for($i = 0; $i < $count; $i++)
            <div class="{{ $baseClass }} h-4 w-full"></div>
        @endfor
    @endif
    
    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>
