{{--
    Editar Ordem de Serviço
--}}

<x-layouts.module title="Editar OS #{{ str_pad((string) $serviceOrder->order_number, 6, '0', STR_PAD_LEFT) }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Ordens de Serviço', 'href' => route('service-orders.index')],
            [
                'label' => 'OS #' . str_pad((string) $serviceOrder->order_number, 6, '0', STR_PAD_LEFT),
                'href' => route('service-orders.show', $serviceOrder),
            ],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-4">
            <span>Editar OS #{{ str_pad((string) $serviceOrder->order_number, 6, '0', STR_PAD_LEFT) }}</span>
            @if ($serviceOrder->status)
                <x-spire::badge :variant="$serviceOrder->status->color ?? 'secondary'" size="lg">
                    {{ $serviceOrder->status->name }}
                </x-spire::badge>
            @endif
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('service-orders.show', $serviceOrder) }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    {{-- Form --}}
    <form action="{{ route('service-orders.update', $serviceOrder) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('service-orders.partials.form', [
            'serviceOrder' => $serviceOrder,
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
            <x-spire::button href="{{ route('service-orders.show', $serviceOrder) }}" variant="secondary">
                Cancelar
            </x-spire::button>
            <x-spire::button type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Salvar Alterações
            </x-spire::button>
        </div>
    </form>
</x-layouts.module>
