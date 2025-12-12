{{--
    Criar nova Ordem de Serviço
--}}

<x-layouts.module title="Nova Ordem de Serviço">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Ordens de Serviço', 'href' => route('service-orders.index')],
            ['label' => 'Nova OS'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Nova Ordem de Serviço
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('service-orders.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cancelar
        </x-spire::button>
    </x-slot:headerActions>

    {{-- Form --}}
    <form action="{{ route('service-orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('service-orders.partials.form', [
            'serviceOrder' => null,
            'partners' => $partners,
            'brands' => $brands,
            'statuses' => $statuses,
            'serviceTypes' => $serviceTypes,
            'serviceLocations' => $serviceLocations,
            'warrantyTypes' => $warrantyTypes,
            'repairTypes' => $repairTypes,
        ])

        {{-- Form Actions --}}
        <div class="mt-6 flex items-center justify-end gap-4">
            <x-spire::button href="{{ route('service-orders.index') }}" variant="secondary">
                Cancelar
            </x-spire::button>
            <x-spire::button type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Criar Ordem de Serviço
            </x-spire::button>
        </div>
    </form>
</x-layouts.module>
