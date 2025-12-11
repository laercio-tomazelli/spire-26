@php
    $typeLabels = [
        'main' => 'Principal',
        'partner' => 'Parceiro',
        'buffer' => 'Buffer',
        'defective' => 'Defeituosos',
    ];
    $typeVariants = [
        'main' => 'primary',
        'partner' => 'info',
        'buffer' => 'warning',
        'defective' => 'danger',
    ];
@endphp

<x-layouts.module :title="$warehouse->name">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Estoque'],
            ['label' => 'Depósitos', 'href' => route('warehouses.index')],
            ['label' => $warehouse->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Detalhes do depósito
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('update', $warehouse)
            <x-spire::button variant="outline" href="{{ route('warehouses.edit', $warehouse) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </x-spire::button>
        @endcan
        @can('delete', $warehouse)
            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="inline"
                onsubmit="return confirm('Tem certeza que deseja excluir este depósito?')">
                @csrf
                @method('DELETE')
                <x-spire::button variant="danger" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Excluir
                </x-spire::button>
            </form>
        @endcan
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    @if (session('error'))
        <x-spire::alert type="danger" class="mb-6">
            {{ session('error') }}
        </x-spire::alert>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <x-spire::card>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Itens</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($stats['total_items']) }}</p>
                </div>
            </div>
        </x-spire::card>

        <x-spire::card>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Qtd. Disponível</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($stats['total_quantity']) }}</p>
                </div>
            </div>
        </x-spire::card>

        <x-spire::card>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Reservados</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($stats['reserved_quantity']) }}</p>
                </div>
            </div>
        </x-spire::card>

        <x-spire::card>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Defeituosos</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($stats['defective_quantity']) }}</p>
                </div>
            </div>
        </x-spire::card>
    </div>

    {{-- Details Card --}}
    <x-spire::card title="Informações do Depósito">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Código</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $warehouse->code }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $warehouse->name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                <dd class="mt-1">
                    <x-spire::badge :variant="$typeVariants[$warehouse->type] ?? 'secondary'">
                        {{ $typeLabels[$warehouse->type] ?? $warehouse->type }}
                    </x-spire::badge>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Parceiro</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $warehouse->partner?->trade_name ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Localização</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $warehouse->location ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $warehouse->description ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Criado em</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $warehouse->created_at->format('d/m/Y H:i') }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Atualizado em</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $warehouse->updated_at->format('d/m/Y H:i') }}
                </dd>
            </div>
        </div>
    </x-spire::card>

    {{-- Inventory Items --}}
    @if ($warehouse->inventoryItems->count() > 0)
        <x-spire::card title="Itens em Estoque" class="mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3">Código</th>
                            <th class="px-4 py-3">Peça</th>
                            <th class="px-4 py-3 text-right">Disponível</th>
                            <th class="px-4 py-3 text-right">Reservado</th>
                            <th class="px-4 py-3 text-right">Defeituoso</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($warehouse->inventoryItems->take(10) as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3 font-mono">{{ $item->part_code }}</td>
                                <td class="px-4 py-3">{{ $item->part?->description ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-medium">
                                    {{ number_format($item->available_quantity) }}</td>
                                <td class="px-4 py-3 text-right text-yellow-600">
                                    {{ number_format($item->reserved_quantity) }}</td>
                                <td class="px-4 py-3 text-right text-red-600">
                                    {{ number_format($item->defective_quantity) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($warehouse->inventoryItems->count() > 10)
                <div class="mt-4 text-center">
                    <a href="{{ route('inventory.index', ['warehouse_id' => $warehouse->id]) }}"
                        class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
                        Ver todos os {{ $warehouse->inventoryItems->count() }} itens →
                    </a>
                </div>
            @endif
        </x-spire::card>
    @endif
</x-layouts.module>
