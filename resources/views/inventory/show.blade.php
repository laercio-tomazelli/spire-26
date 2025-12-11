<x-layouts.module :title="'Estoque: ' . $item->part_code">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Estoque'],
            ['label' => 'Itens', 'href' => route('inventory.index')],
            ['label' => $item->part_code],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Detalhes do item de estoque
    </x-slot:header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <x-spire::card>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <x-spire::icon name="check-circle" size="lg" class="text-green-600 dark:text-green-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Disponível</p>
                    <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                        {{ number_format($item->available_quantity) }}</p>
                </div>
            </div>
        </x-spire::card>

        <x-spire::card>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <x-spire::icon name="clock" size="lg" class="text-yellow-600 dark:text-yellow-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Reservado</p>
                    <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">
                        {{ number_format($item->reserved_quantity) }}</p>
                </div>
            </div>
        </x-spire::card>

        <x-spire::card>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900">
                    <x-spire::icon name="clock" size="lg" class="text-orange-600 dark:text-orange-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendente</p>
                    <p class="text-2xl font-semibold text-orange-600 dark:text-orange-400">
                        {{ number_format($item->pending_quantity ?? 0) }}</p>
                </div>
            </div>
        </x-spire::card>

        <x-spire::card>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                    <x-spire::icon name="exclamation-triangle" size="lg" class="text-red-600 dark:text-red-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Defeituoso</p>
                    <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                        {{ number_format($item->defective_quantity ?? 0) }}</p>
                </div>
            </div>
        </x-spire::card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Informações da Peça --}}
        <x-spire::card title="Informações da Peça">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Código</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $item->part_code }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $item->part?->description ?? '—' }}</dd>
                </div>

                @if ($item->part?->short_description)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição Curta</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $item->part->short_description }}</dd>
                    </div>
                @endif

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unidade</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $item->part?->unit ?? '—' }}</dd>
                </div>

                @if ($item->part)
                    <div class="pt-2">
                        <a href="{{ route('parts.show', $item->part) }}"
                            class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
                            Ver detalhes da peça →
                        </a>
                    </div>
                @endif
            </dl>
        </x-spire::card>

        {{-- Informações do Depósito --}}
        <x-spire::card title="Informações do Depósito">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Código</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                        {{ $item->warehouse?->code ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $item->warehouse?->name ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                    <dd class="mt-1">
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
                        <x-spire::badge :variant="$typeVariants[$item->warehouse?->type] ?? 'secondary'">
                            {{ $typeLabels[$item->warehouse?->type] ?? ($item->warehouse?->type ?? '—') }}
                        </x-spire::badge>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Localização</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $item->warehouse?->location ?? '—' }}
                    </dd>
                </div>

                @if ($item->warehouse)
                    <div class="pt-2">
                        <a href="{{ route('warehouses.show', $item->warehouse) }}"
                            class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
                            Ver detalhes do depósito →
                        </a>
                    </div>
                @endif
            </dl>
        </x-spire::card>
    </div>

    {{-- Últimas Movimentações --}}
    @if ($item->transactions && $item->transactions->count() > 0)
        <x-spire::card title="Últimas Movimentações" class="mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3 text-right">Quantidade</th>
                            <th class="px-4 py-3">Documento</th>
                            <th class="px-4 py-3">Usuário</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($item->transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <x-spire::badge :variant="$transaction->quantity > 0 ? 'success' : 'danger'">
                                        {{ $transaction->quantity > 0 ? 'Entrada' : 'Saída' }}
                                    </x-spire::badge>
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-medium {{ $transaction->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->quantity > 0 ? '+' : '' }}{{ number_format($transaction->quantity) }}
                                </td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $transaction->document_number ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $transaction->user?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-spire::card>
    @endif
</x-layouts.module>
