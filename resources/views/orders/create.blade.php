<x-layouts.module title="Novo Pedido">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Pedidos', 'href' => route('orders.index')],
            ['label' => 'Novo Pedido'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Criar novo pedido
    </x-slot:header>

    {{-- Conteúdo da página --}}
    <div>
        <!-- Formulário para criar pedido aqui -->
    </div>
</x-layouts.module>
