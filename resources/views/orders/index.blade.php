<x-layouts.module title="Pedidos">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Pedidos']]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Gerencie os pedidos do sistema
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('orders.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Pedido
        </x-spire::button>

        <x-spire::button href="{{ route('orders.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Pedido
        </x-spire::button>
    </x-slot:headerActions>

    {{-- Conteúdo da página --}}
    <x-spire::card title="Lista de Pedidos">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <x-spire::button size="sm" class="!bg-sky-100 !text-sky-500 hover:!bg-blue-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtrar
                </x-spire::button>
                <x-spire::button size="sm" class="!bg-blue-100 !text-blue-500 hover:!bg-blue-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Exportar
                </x-spire::button>
            </div>
        </x-slot:actions>

        <div class="text-center py-8 text-gray-500">
            Nenhum pedido encontrado
        </div>
    </x-spire::card>
</x-layouts.module>
