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
                <x-spire::icon name="edit" size="sm" class="mr-2" />
                Editar
            </x-spire::button>
        @endcan
        @can('delete', $warehouse)
            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="inline"
                onsubmit="return confirm('Tem certeza que deseja excluir este depósito?')">
                @csrf
                @method('DELETE')
                <x-spire::button variant="danger" type="submit">
                    <x-spire::icon name="trash" size="sm" class="mr-2" />
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
                    <x-spire::icon name="cube" size="lg" class="text-blue-600 dark:text-blue-400" />
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
                    <x-spire::icon name="check-circle" size="lg" class="text-green-600 dark:text-green-400" />
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
                    <x-spire::icon name="clock" size="lg" class="text-yellow-600 dark:text-yellow-400" />
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
                    <x-spire::icon name="exclamation-triangle" size="lg" class="text-red-600 dark:text-red-400" />
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
    @if ($inventoryItems->count() > 0)
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
                        @foreach ($inventoryItems->take(10) as $item)
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
            @if ($inventoryItems->count() > 10)
                <div class="mt-4 text-center">
                    <a href="{{ route('inventory.index', ['warehouse_id' => $warehouse->id]) }}"
                        class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
                        Ver todos os {{ $inventoryItems->count() }} itens →
                    </a>
                </div>
            @endif
        </x-spire::card>
    @endif
</x-layouts.module>
