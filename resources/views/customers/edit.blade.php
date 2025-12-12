<x-layouts.module title="Editar Cliente">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Clientes', 'href' => route('customers.index')],
            ['label' => $customer->name, 'href' => route('customers.show', $customer)],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Atualize os dados do cliente
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('customers.show', $customer) }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('customers.update', $customer) }}" method="POST" id="customer-form">
        @csrf
        @method('PUT')
        @include('customers.partials.form', ['customer' => $customer, 'states' => $states])
    </form>
</x-layouts.module>
