<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Cardif' }} · {{ config('app.name', 'SPIRE') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/spire/global.ts'])

    <style>
        #cardif-sidebar.sidebar-collapsed {
            width: 4rem !important;
        }

        #cardif-sidebar.sidebar-collapsed .sidebar-item-text,
        #cardif-sidebar.sidebar-collapsed .sidebar-item-arrow,
        #cardif-sidebar.sidebar-collapsed .sidebar-logo-full {
            display: none;
        }

        #cardif-sidebar.sidebar-collapsed [data-submenu] {
            display: none !important;
        }

        #cardif-sidebar.sidebar-collapsed .toggle-icon {
            transform: rotate(180deg);
        }

        #cardif-sidebar.sidebar-collapsed .sidebar-header {
            justify-content: center;
        }

        #cardif-sidebar.sidebar-collapsed .sidebar-item {
            justify-content: center;
        }

        #cardif-sidebar.sidebar-collapsed~#cardif-navbar {
            left: 4rem !important;
        }

        #cardif-sidebar.sidebar-collapsed~#cardif-navbar [data-navbar-logo] {
            opacity: 1 !important;
            width: auto !important;
            overflow: visible !important;
        }

        #cardif-sidebar.sidebar-collapsed~#cardif-main {
            left: 4rem !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <x-ui.icons />
    <div class="relative min-h-screen">
        {{-- Sidebar --}}
        <aside id="cardif-sidebar" data-v="sidebar" data-persist="cardif"
            class="sidebar fixed inset-y-0 left-0 flex flex-col z-20
                   bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700
                   transition-all duration-300 ease-in-out w-60">
            {{-- Header --}}
            <div
                class="sidebar-header flex items-center justify-between h-14 px-3 border-b border-gray-200 dark:border-gray-700 shrink-0">
                <div class="flex items-center gap-2 overflow-hidden sidebar-logo-full">
                    <div
                        class="w-9 h-9 bg-linear-to-br from-[#0050B3] to-[#13C2C2] rounded-lg flex items-center justify-center text-white font-bold text-sm shrink-0">
                        C
                    </div>
                    <span class="sidebar-item-text font-semibold text-gray-800 dark:text-gray-200 whitespace-nowrap">
                        Cardif
                    </span>
                </div>
                <button type="button" data-sidebar-toggle
                    class="sidebar-toggle-btn w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="toggle-icon w-4 h-4 transition-transform duration-300" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-3 px-2">
                <ul class="space-y-1">
                    <li data-sidebar-item="dashboard">
                        <a href="{{ route('cardif.dashboard') }}" @class([
                            'sidebar-item flex items-center w-full px-3 py-2 rounded-lg transition-all duration-200',
                            'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' => request()->routeIs(
                                'cardif.dashboard'),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' => !request()->routeIs(
                                'cardif.dashboard'),
                        ])>
                            <span class="shrink-0 w-5 h-5">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </span>
                            <span class="sidebar-item-text ml-3 text-sm whitespace-nowrap">Dashboard</span>
                        </a>
                    </li>
                    <li data-sidebar-item="inspections">
                        <a href="{{ route('cardif.inspections.index') }}" @class([
                            'sidebar-item flex items-center w-full px-3 py-2 rounded-lg transition-all duration-200',
                            'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' => request()->routeIs(
                                'cardif.inspections.*'),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' => !request()->routeIs(
                                'cardif.inspections.*'),
                        ])>
                            <span class="shrink-0 w-5 h-5">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <span class="sidebar-item-text ml-3 text-sm whitespace-nowrap">Vistorias</span>
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- Footer --}}
            <div class="border-t border-gray-200 dark:border-gray-700 p-3 shrink-0">
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <span class="sidebar-item-text">Tenant: Cardif</span>
                </div>
            </div>
        </aside>

        {{-- Topbar --}}
        <header id="cardif-navbar" data-v="navbar" data-sidebar="cardif-sidebar"
            class="fixed top-0 right-0 left-60 h-14 z-10
                   bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700
                   transition-all duration-300 ease-in-out">
            <div class="h-full flex items-center justify-between px-4">
                {{-- Logo (visible when sidebar collapsed) --}}
                <div class="flex items-center">
                    <div data-navbar-logo
                        class="flex items-center gap-2 transition-all duration-300 opacity-0 w-0 overflow-hidden">
                        <div
                            class="w-8 h-8 bg-linear-to-br from-[#0050B3] to-[#13C2C2] rounded-lg flex items-center justify-center text-white font-bold text-xs shrink-0">
                            C
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-200 whitespace-nowrap text-sm">
                            Cardif
                        </span>
                    </div>
                </div>

                {{-- Page heading (slot) --}}
                <div class="flex-1 px-6">
                    @isset($header)
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $header }}</div>
                    @endisset
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    <button
                        class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        title="Notificações">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>

                    <x-spire::dropdown align="right" width="w-48">
                        <x-slot:triggerSlot>
                            <button
                                class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                                        {{ Str::upper(Str::substr(Auth::user()?->name ?? 'C', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="hidden sm:inline text-sm text-gray-700 dark:text-gray-200">
                                    {{ Auth::user()?->name ?? 'Convidado' }}
                                </span>
                            </button>
                        </x-slot:triggerSlot>

                        @auth
                            <x-spire::dropdown-item :href="route('profile.edit')">{{ __('Perfil') }}</x-spire::dropdown-item>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-spire::dropdown-item :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Sair') }}
                                </x-spire::dropdown-item>
                            </form>
                        @else
                            <x-spire::dropdown-item :href="route('login')">{{ __('Entrar') }}</x-spire::dropdown-item>
                        @endauth
                    </x-spire::dropdown>
                </div>
            </div>
        </header>

        {{-- Main content --}}
        <main id="cardif-main" class="absolute inset-0 top-14 left-60 transition-all duration-300 overflow-y-auto">
            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>
