@props([
    'id' => null,
    'sidebar' => null,
    'fixed' => true,
    'logo' => null,
    'logoCollapsed' => null,
    'center' => null,
    'sac' => null,
])

@php
    $navbarId = $id ?? 'navbar-' . uniqid();
    $positionClass = $fixed ? 'fixed top-0 right-0 left-0 z-30' : 'relative';
@endphp

<header 
    id="{{ $navbarId }}"
    data-v="navbar"
    @if($sidebar) data-sidebar="{{ $sidebar }}" @endif
    {{ $attributes->merge([
        'class' => "{$positionClass} h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800
                    transition-all duration-300 ease-in-out
                    lg:pl-64"
    ]) }}
>
    <div class="h-full flex items-center justify-between px-4 lg:px-6">
        {{-- Área 1: Logo (visível quando sidebar está colapsada) + Mobile Menu --}}
        <div class="flex items-center gap-4">
            {{-- Mobile menu button --}}
            <button 
                type="button"
                data-navbar-mobile-toggle
                class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                aria-label="Abrir menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Logo container (hidden when sidebar is expanded on desktop) --}}
            <div 
                data-navbar-logo 
                class="flex items-center gap-3 transition-all duration-300 opacity-0 w-0 overflow-hidden lg:opacity-0 lg:w-0"
            >
                @if($logo)
                    {{ $logo }}
                @endif
            </div>
        </div>

        {{-- Área 2: Centro (logo do cliente/parceiro, etc) --}}
        <div class="flex items-center justify-center flex-1 px-4">
            @if($center)
                {{ $center }}
            @endif
        </div>

        {{-- Área 3: SAC / Contato --}}
        <div class="hidden md:flex items-center gap-2 text-gray-600 dark:text-gray-400">
            @if($sac)
                {{ $sac }}
            @else
                {{-- Default SAC slot --}}
            @endif
        </div>

        {{-- Área 4: Ações (notificações, perfil, etc) --}}
        <div class="flex items-center gap-2 ml-4">
            {{ $slot }}
        </div>
    </div>
</header>

{{-- Spacer para compensar navbar fixed --}}
@if($fixed)
    <div class="h-16"></div>
@endif
