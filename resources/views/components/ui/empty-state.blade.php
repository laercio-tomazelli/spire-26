@props([
    'title' => 'Nenhum resultado encontrado',
    'description' => null,
    'icon' => 'search',
    'iconColor' => 'gray',
    'variant' => 'default', // default, compact, card
    'actionLabel' => null,
    'actionUrl' => null,
    'secondaryLabel' => null,
    'secondaryUrl' => null,
])

@php
    $iconColors = [
        'gray' => 'text-gray-300 dark:text-gray-600',
        'primary' => 'text-primary-300 dark:text-primary-700',
        'success' => 'text-green-300 dark:text-green-700',
        'warning' => 'text-yellow-300 dark:text-yellow-700',
        'danger' => 'text-red-300 dark:text-red-700',
        'info' => 'text-blue-300 dark:text-blue-700',
    ][$iconColor] ?? 'text-gray-300 dark:text-gray-600';

    $presetIcons = [
        'search' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>',
        'inbox' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>',
        'folder' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
        'users' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
        'document' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
        'cart' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        'calendar' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        'bell' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>',
        'image' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        'error' => '<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
    ];
@endphp

@if($variant === 'compact')
    {{-- Compact Variant --}}
    <div {{ $attributes->merge(['class' => 'flex items-center gap-4 p-4']) }}>
        <div class="{{ $iconColors }}">
            @if(isset($presetIcons[$icon]))
                {!! str_replace('w-16 h-16', 'w-10 h-10', $presetIcons[$icon]) !!}
            @else
                <x-ui.icon :name="$icon" size="xl" />
            @endif
        </div>
        <div>
            <h3 class="font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
            @if($description)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $description }}</p>
            @endif
        </div>
    </div>

@elseif($variant === 'card')
    {{-- Card Variant --}}
    <div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-8']) }}>
        <div class="text-center">
            <div class="{{ $iconColors }} mb-4">
                @if(isset($presetIcons[$icon]))
                    {!! $presetIcons[$icon] !!}
                @else
                    <x-ui.icon :name="$icon" size="2xl" class="mx-auto" />
                @endif
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            @if($description)
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">{{ $description }}</p>
            @endif
            
            @if($actionLabel || $secondaryLabel)
                <div class="mt-6 flex items-center justify-center gap-3">
                    @if($actionLabel)
                        <a href="{{ $actionUrl ?? '#' }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all">
                            {{ $actionLabel }}
                        </a>
                    @endif
                    @if($secondaryLabel)
                        <a href="{{ $secondaryUrl ?? '#' }}" class="inline-flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 font-medium hover:text-gray-900 dark:hover:text-white transition-colors">
                            {{ $secondaryLabel }}
                        </a>
                    @endif
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>

@else
    {{-- Default Variant --}}
    <div {{ $attributes->merge(['class' => 'py-12']) }}>
        <div class="text-center">
            <div class="{{ $iconColors }} mb-4">
                @if(isset($presetIcons[$icon]))
                    {!! $presetIcons[$icon] !!}
                @else
                    <x-ui.icon :name="$icon" size="2xl" class="mx-auto" />
                @endif
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            @if($description)
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">{{ $description }}</p>
            @endif
            
            @if($actionLabel || $secondaryLabel)
                <div class="mt-6 flex items-center justify-center gap-3">
                    @if($actionLabel)
                        <a href="{{ $actionUrl ?? '#' }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all">
                            {{ $actionLabel }}
                        </a>
                    @endif
                    @if($secondaryLabel)
                        <a href="{{ $secondaryUrl ?? '#' }}" class="inline-flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 font-medium hover:text-gray-900 dark:hover:text-white transition-colors">
                            {{ $secondaryLabel }}
                        </a>
                    @endif
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
@endif
