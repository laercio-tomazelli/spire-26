@props([
    'items' => [],
    'variant' => 'default', // default, alternating, compact
    'color' => 'primary',
    'iconSize' => 'md',
])

@php
    $colorClasses = [
        'primary' => 'bg-primary-500',
        'gray' => 'bg-gray-500',
        'success' => 'bg-green-500',
        'warning' => 'bg-yellow-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-blue-500',
    ][$color] ?? 'bg-primary-500';

    $iconSizes = [
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
    ][$iconSize] ?? 'w-10 h-10';
@endphp

<div {{ $attributes->merge(['class' => 'relative']) }}>
    @if($variant === 'alternating')
        {{-- Alternating Timeline --}}
        <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-0.5 bg-gray-200 dark:bg-gray-700"></div>
        
        @foreach($items as $index => $item)
            @php
                $isLeft = $index % 2 === 0;
                $itemColor = $item['color'] ?? $color;
                $dotColor = [
                    'primary' => 'bg-primary-500',
                    'gray' => 'bg-gray-500',
                    'success' => 'bg-green-500',
                    'warning' => 'bg-yellow-500',
                    'danger' => 'bg-red-500',
                    'info' => 'bg-blue-500',
                ][$itemColor] ?? $colorClasses;
            @endphp
            <div class="relative flex items-center mb-8 {{ $isLeft ? 'justify-start' : 'justify-end' }}">
                {{-- Dot --}}
                <div class="absolute left-1/2 transform -translate-x-1/2 {{ $iconSizes }} rounded-full {{ $dotColor }} flex items-center justify-center text-white shadow-lg z-10">
                    @if(isset($item['icon']))
                        <x-ui.icon :name="$item['icon']" size="sm" />
                    @else
                        <span class="text-xs font-bold">{{ $index + 1 }}</span>
                    @endif
                </div>
                
                {{-- Content --}}
                <div class="w-5/12 {{ $isLeft ? 'pr-8 text-right' : 'pl-8 text-left' }}">
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-100 dark:border-gray-700">
                        @if(isset($item['date']))
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item['date'] }}</span>
                        @endif
                        <h4 class="font-semibold text-gray-900 dark:text-white mt-1">{{ $item['title'] ?? '' }}</h4>
                        @if(isset($item['description']))
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

    @elseif($variant === 'compact')
        {{-- Compact Timeline --}}
        <div class="space-y-3">
            @foreach($items as $index => $item)
                @php
                    $itemColor = $item['color'] ?? $color;
                    $dotColor = [
                        'primary' => 'bg-primary-500',
                        'gray' => 'bg-gray-500',
                        'success' => 'bg-green-500',
                        'warning' => 'bg-yellow-500',
                        'danger' => 'bg-red-500',
                        'info' => 'bg-blue-500',
                    ][$itemColor] ?? $colorClasses;
                @endphp
                <div class="flex items-start gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-2.5 h-2.5 rounded-full {{ $dotColor }} mt-1.5"></div>
                        @if(!$loop->last)
                            <div class="w-0.5 h-full min-h-[20px] bg-gray-200 dark:bg-gray-700"></div>
                        @endif
                    </div>
                    <div class="flex-1 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $item['title'] ?? '' }}</span>
                            @if(isset($item['date']))
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item['date'] }}</span>
                            @endif
                        </div>
                        @if(isset($item['description']))
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    @else
        {{-- Default Timeline --}}
        <div class="absolute left-5 top-0 h-full w-0.5 bg-gray-200 dark:bg-gray-700"></div>
        
        @foreach($items as $index => $item)
            @php
                $itemColor = $item['color'] ?? $color;
                $dotColor = [
                    'primary' => 'bg-primary-500',
                    'gray' => 'bg-gray-500',
                    'success' => 'bg-green-500',
                    'warning' => 'bg-yellow-500',
                    'danger' => 'bg-red-500',
                    'info' => 'bg-blue-500',
                ][$itemColor] ?? $colorClasses;
            @endphp
            <div class="relative flex items-start mb-8 last:mb-0">
                {{-- Dot/Icon --}}
                <div class="{{ $iconSizes }} rounded-full {{ $dotColor }} flex items-center justify-center text-white shadow-lg z-10 flex-shrink-0">
                    @if(isset($item['icon']))
                        <x-ui.icon :name="$item['icon']" size="sm" />
                    @else
                        <span class="text-xs font-bold">{{ $index + 1 }}</span>
                    @endif
                </div>
                
                {{-- Content --}}
                <div class="ml-6 flex-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between gap-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $item['title'] ?? '' }}</h4>
                            @if(isset($item['date']))
                                <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $item['date'] }}</span>
                            @endif
                        </div>
                        @if(isset($item['description']))
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $item['description'] }}</p>
                        @endif
                        @if(isset($item['tags']))
                            <div class="flex flex-wrap gap-1 mt-3">
                                @foreach($item['tags'] as $tag)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{ $slot }}
</div>
