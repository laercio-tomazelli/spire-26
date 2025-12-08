@props([
    'count' => 0,
    'maxCount' => 99,
    'color' => 'danger',
    'size' => 'md',
    'pulse' => false,
    'notifications' => [],
    'emptyMessage' => 'Nenhuma notificação',
    'showDropdown' => true,
])

@php
    $sizeClasses = [
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
    ][$size] ?? 'w-10 h-10';

    $iconSizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ][$size] ?? 'w-5 h-5';

    $badgeColors = [
        'danger' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'success' => 'bg-green-500',
        'info' => 'bg-blue-500',
        'primary' => 'bg-primary-500',
    ][$color] ?? 'bg-red-500';

    $displayCount = $count > $maxCount ? "{$maxCount}+" : $count;
@endphp

<div {{ $attributes->merge(['class' => 'relative']) }} data-v="dropdown">
    {{-- Bell Button --}}
    <button
        type="button"
        class="{{ $sizeClasses }} relative flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        data-dropdown-trigger
        aria-label="Notificações"
    >
        {{-- Bell Icon --}}
        <svg class="{{ $iconSizes }} text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        {{-- Badge --}}
        @if($count > 0)
            <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white {{ $badgeColors }} rounded-full {{ $pulse ? 'animate-pulse' : '' }}">
                {{ $displayCount }}
            </span>
        @endif

        {{-- Pulse Ring --}}
        @if($pulse && $count > 0)
            <span class="absolute -top-1 -right-1 w-[18px] h-[18px] {{ $badgeColors }} rounded-full animate-ping opacity-75"></span>
        @endif
    </button>

    {{-- Dropdown --}}
    @if($showDropdown)
        <div 
            data-dropdown-menu
            class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 opacity-0 invisible transform scale-95 transition-all duration-200 z-50"
        >
            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Notificações</h3>
                @if($count > 0)
                    <button type="button" class="text-xs text-primary-500 hover:text-primary-600 dark:hover:text-primary-400">
                        Marcar todas como lidas
                    </button>
                @endif
            </div>

            {{-- Notifications List --}}
            <div class="max-h-96 overflow-y-auto">
                @forelse($notifications as $notification)
                    <a 
                        href="{{ $notification['url'] ?? '#' }}" 
                        class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !($notification['read'] ?? false) ? 'bg-primary-50 dark:bg-primary-900/20' : '' }}"
                    >
                        {{-- Icon/Avatar --}}
                        @if(isset($notification['avatar']))
                            <img src="{{ $notification['avatar'] }}" alt="" class="w-10 h-10 rounded-full object-cover shrink-0">
                        @elseif(isset($notification['icon']))
                            <div class="w-10 h-10 rounded-full bg-{{ $notification['iconColor'] ?? 'gray' }}-100 dark:bg-{{ $notification['iconColor'] ?? 'gray' }}-900/30 flex items-center justify-center shrink-0">
                                <x-ui.icon :name="$notification['icon']" class="text-{{ $notification['iconColor'] ?? 'gray' }}-500" />
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                                <x-ui.icon name="bell" class="text-gray-500" />
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white {{ !($notification['read'] ?? false) ? 'font-medium' : '' }}">
                                {{ $notification['title'] ?? '' }}
                            </p>
                            @if(isset($notification['description']))
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                    {{ $notification['description'] }}
                                </p>
                            @endif
                            @if(isset($notification['time']))
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ $notification['time'] }}
                                </p>
                            @endif
                        </div>

                        {{-- Unread indicator --}}
                        @if(!($notification['read'] ?? false))
                            <span class="w-2 h-2 rounded-full bg-primary-500 shrink-0 mt-2"></span>
                        @endif
                    </a>
                @empty
                    <div class="px-4 py-8 text-center">
                        <x-ui.icon name="bell" size="xl" class="text-gray-300 dark:text-gray-600 mx-auto mb-3" />
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $emptyMessage }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Footer --}}
            @if(count($notifications) > 0)
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="#" class="block text-center text-sm text-primary-500 hover:text-primary-600 dark:hover:text-primary-400 font-medium">
                        Ver todas as notificações
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
