{{--
    Layout Padrão para Módulos do SPIRE

    Usage:
    <x-layouts.module title="Dashboard">
        <!-- Conteúdo do módulo -->
    </x-layouts.module>

    Slots disponíveis:
    - $title: Título da página (obrigatório)
    - $header: Conteúdo adicional no header da página
    - $headerActions: Botões de ação no header
    - $breadcrumbs: Breadcrumbs customizados
--}}

@props([
    'title' => '',
])

<x-layouts.app>
    {{-- Sidebar --}}
    <x-spire::sidebar id="main-sidebar" persist="spire-sidebar">
        {{-- Logo --}}
        <x-slot:logo>
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                    S
                </div>
                <span class="font-semibold text-gray-900 dark:text-white">SPIRE</span>
            </div>
        </x-slot:logo>

        {{-- Logo Collapsed --}}
        <x-slot:logoCollapsed>
            <div
                class="w-8 h-8 rounded-lg bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                S
            </div>
        </x-slot:logoCollapsed>

        {{-- Menu Principal --}}
        <x-spire::sidebar-group label="Principal">
            <x-spire::sidebar-item href="{{ route('dashboard') }}" label="Dashboard" :active="request()->routeIs('dashboard')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>' />
        </x-spire::sidebar-group>

        {{-- Operações --}}
        <x-spire::sidebar-group label="Operações">
            <x-spire::sidebar-item label="Ordens de Serviço" :submenu="true" :active="request()->routeIs('service-orders.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>'>
                <x-spire::sidebar-item href="{{ route('service-orders.index') }}" label="Todas as OS"
                    :active="request()->routeIs('service-orders.index')" />
                <x-spire::sidebar-item href="{{ route('service-orders.index', ['status' => 'open']) }}" label="Abertas"
                    :active="request()->is('service-orders*') && request()->get('status') === 'open'" />
                <x-spire::sidebar-item href="{{ route('service-orders.index', ['status' => 'closed']) }}"
                    label="Finalizadas" :active="request()->is('service-orders*') && request()->get('status') === 'closed'" />
                <x-spire::sidebar-item href="{{ route('service-orders.create') }}" label="Nova OS" :active="request()->routeIs('service-orders.create')" />
            </x-spire::sidebar-item>

            <x-spire::sidebar-item label="Pedidos de Peças" :submenu="true"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'>
                <x-spire::sidebar-item href="#" label="Novo Pedido" />
                <x-spire::sidebar-item href="#" label="Meus Pedidos" />
                <x-spire::sidebar-item href="#" label="Histórico" />
            </x-spire::sidebar-item>

            <x-spire::sidebar-item href="#" label="Trocas"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>' />

            {{-- Cadastros (submenu colapsável) --}}
            <x-spire::sidebar-item label="Cadastros" :submenu="true" :active="request()->routeIs('customers.*') ||
                request()->routeIs('product-models.*') ||
                request()->routeIs('product-lines.*') ||
                request()->routeIs('product-categories.*') ||
                request()->routeIs('parts.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>'>
                <x-spire::sidebar-item href="{{ route('customers.index') }}" label="Clientes" :active="request()->routeIs('customers.*')" />
                <x-spire::sidebar-item label="Produtos" :submenu="true" :active="request()->routeIs('product-models.*') ||
                    request()->routeIs('product-lines.*') ||
                    request()->routeIs('product-categories.*')">
                    <x-spire::sidebar-item href="{{ route('product-models.index') }}" label="Modelos"
                        :active="request()->routeIs('product-models.*')" />
                    <x-spire::sidebar-item href="{{ route('product-lines.index') }}" label="Linhas"
                        :active="request()->routeIs('product-lines.*')" />
                    <x-spire::sidebar-item href="{{ route('product-categories.index') }}" label="Categorias"
                        :active="request()->routeIs('product-categories.*')" />
                </x-spire::sidebar-item>
                <x-spire::sidebar-item href="{{ route('parts.index') }}" label="Peças" :active="request()->routeIs('parts.*')" />
            </x-spire::sidebar-item>

            {{-- Estoque (submenu colapsável) --}}
            <x-spire::sidebar-item label="Estoque" :submenu="true" :active="request()->routeIs('warehouses.*') ||
                request()->routeIs('inventory.*') ||
                request()->routeIs('inventory-transactions.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'>
                <x-spire::sidebar-item href="{{ route('warehouses.index') }}" label="Depósitos" :active="request()->routeIs('warehouses.*')" />
                <x-spire::sidebar-item href="{{ route('inventory.index') }}" label="Itens" :active="request()->routeIs('inventory.*')" />
                <x-spire::sidebar-item href="{{ route('inventory-transactions.index') }}" label="Movimentações"
                    :active="request()->routeIs('inventory-transactions.*')" />
            </x-spire::sidebar-item>

            {{-- Administração (submenu colapsável) --}}
            <x-spire::sidebar-item label="Administração" :submenu="true" :active="request()->routeIs('tenants.*') ||
                request()->routeIs('manufacturers.*') ||
                request()->routeIs('users.*') ||
                request()->routeIs('teams.*') ||
                request()->routeIs('roles.*') ||
                request()->routeIs('permissions.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'>
                <x-spire::sidebar-item href="{{ route('tenants.index') }}" label="Tenants" :active="request()->routeIs('tenants.*')" />
                <x-spire::sidebar-item href="{{ route('manufacturers.index') }}" label="Fabricantes"
                    :active="request()->routeIs('manufacturers.*')" />
                <x-spire::sidebar-item href="{{ route('users.index') }}" label="Usuários" :active="request()->routeIs('users.*')" />
                <x-spire::sidebar-item href="{{ route('teams.index') }}" label="Times" :active="request()->routeIs('teams.*')" />
                <x-spire::sidebar-item href="{{ route('roles.index') }}" label="Perfis" :active="request()->routeIs('roles.*')" />
                <x-spire::sidebar-item href="{{ route('permissions.index') }}" label="Permissões" :active="request()->routeIs('permissions.*')" />
            </x-spire::sidebar-item>
        </x-spire::sidebar-group>

        {{-- Footer --}}
        <x-slot:footer>
            <div class="flex items-center gap-3">
                <x-spire::avatar size="sm" :name="Auth::user()?->name ?? 'User'" />
                <div class="sidebar-item-text overflow-hidden transition-all duration-300">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                        {{ Auth::user()?->name ?? 'Usuário' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{ Auth::user()?->email ?? 'email@example.com' }}
                    </p>
                </div>
            </div>
        </x-slot:footer>
    </x-spire::sidebar>

    {{-- Main Content Area --}}
    <div class="lg:ml-64 transition-all duration-300">
        {{-- Navbar --}}
        <x-spire::navbar sidebar="main-sidebar">
            {{-- Logo no navbar (visível quando sidebar colapsada) --}}
            <x-slot:logo>
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-lg bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                        S
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white">SPIRE</span>
                </div>
            </x-slot:logo>

            {{-- Centro (logo do fabricante/parceiro) --}}
            <x-slot:center>
                {{-- Pode ser substituído por logo do manufacturer/partner atual --}}
            </x-slot:center>

            {{-- SAC --}}
            <x-slot:sac>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span class="text-sm">0800 123 4567</span>
                </div>
            </x-slot:sac>

            {{-- Ações do navbar --}}
            <x-spire::notification-bell :count="3" />

            <x-spire::dropdown align="right" width="w-48">
                <x-slot:triggerSlot>
                    <span
                        class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <x-spire::avatar size="sm" :name="Auth::user()?->name ?? 'User'" />
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </x-slot:triggerSlot>

                <x-spire::dropdown-item :href="route('profile.edit')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Meu Perfil
                </x-spire::dropdown-item>

                <x-spire::dropdown-divider />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-spire::dropdown-item :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sair
                    </x-spire::dropdown-item>
                </form>
            </x-spire::dropdown>
        </x-spire::navbar>

        {{-- Page Content --}}
        <main class="min-h-screen">
            {{-- Page Header --}}
            <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    {{-- Breadcrumbs --}}
                    @if (isset($breadcrumbs))
                        <div class="mb-4">
                            {{ $breadcrumbs }}
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $title }}
                            </h1>
                            @if (isset($header))
                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $header }}
                                </div>
                            @endif
                        </div>

                        @if (isset($headerActions))
                            <div class="flex items-center gap-3">
                                {{ $headerActions }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</x-layouts.app>
