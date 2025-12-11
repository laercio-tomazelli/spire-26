{{--
    Tabela parcial para carregamento AJAX - Movimentações de Estoque
--}}

<x-ui.table.columns>
    <x-ui.table.column name="date" :sortable="true" :sorted="request('sort') === 'created_at'" :direction="request('direction', 'desc')">
        Data
    </x-ui.table.column>
    <x-ui.table.column name="part" :sortable="true" :sorted="request('sort') === 'part_code'" :direction="request('direction', 'desc')">
        Peça
    </x-ui.table.column>
    <x-ui.table.column name="warehouse">
        Depósito
    </x-ui.table.column>
    <x-ui.table.column name="quantity" :sortable="true" :sorted="request('sort') === 'quantity'" :direction="request('direction', 'desc')">
        Quantidade
    </x-ui.table.column>
    <x-ui.table.column name="document">
        Documento
    </x-ui.table.column>
    <x-ui.table.column name="user">
        Usuário
    </x-ui.table.column>
    <x-ui.table.column name="actions" class="w-12">
    </x-ui.table.column>
</x-ui.table.columns>

<x-ui.table.body>
    @forelse($transactions as $transaction)
        <x-ui.table.row :key="$transaction->id">
            <x-ui.table.cell name="date">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ $transaction->created_at->format('d/m/Y') }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $transaction->created_at->format('H:i') }}
                </div>
            </x-ui.table.cell>

            <x-ui.table.cell name="part">
                @if ($transaction->part)
                    <a href="{{ route('parts.show', $transaction->part) }}"
                        class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-200">
                        <div class="font-medium">{{ $transaction->part_code }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ Str::limit($transaction->part->description, 40) }}</div>
                    </a>
                @else
                    <div class="font-medium">{{ $transaction->part_code }}</div>
                    <div class="text-xs text-gray-500">(peça não encontrada)</div>
                @endif
            </x-ui.table.cell>

            <x-ui.table.cell name="warehouse">
                @if ($transaction->warehouse)
                    <a href="{{ route('warehouses.show', $transaction->warehouse) }}"
                        class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-200">
                        <div class="font-medium">{{ $transaction->warehouse->code }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->warehouse->name }}</div>
                    </a>
                @else
                    <span class="text-gray-500">-</span>
                @endif
            </x-ui.table.cell>

            <x-ui.table.cell name="quantity">
                @php
                    $isPositive = $transaction->quantity > 0;
                    $colorClass = $isPositive ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                    $bgClass = $isPositive ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20';
                @endphp
                <span class="{{ $colorClass }} {{ $bgClass }} px-2 py-1 rounded-md text-sm font-bold">
                    {{ $isPositive ? '+' : '' }}{{ number_format($transaction->quantity, 0, ',', '.') }}
                </span>
            </x-ui.table.cell>

            <x-ui.table.cell name="document">
                @if ($transaction->document_number)
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $transaction->document_number }}
                    </div>
                    @if ($transaction->documentType)
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $transaction->documentType->name }}
                        </div>
                    @endif
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </x-ui.table.cell>

            <x-ui.table.cell name="user">
                @if ($transaction->user)
                    <div class="flex items-center gap-2">
                        <div
                            class="h-6 w-6 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-xs font-medium text-primary-700 dark:text-primary-300">
                            {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                        </div>
                        <span
                            class="text-sm text-gray-900 dark:text-white">{{ Str::limit($transaction->user->name, 20) }}</span>
                    </div>
                @else
                    <span class="text-gray-400">Sistema</span>
                @endif
            </x-ui.table.cell>

            <x-ui.table.cell name="actions">
                <x-ui.table.actions>
                    <x-ui.table.action href="{{ route('inventory-transactions.show', $transaction) }}"
                        icon="heroicon-o-eye" label="Ver detalhes" />
                </x-ui.table.actions>
            </x-ui.table.cell>
        </x-ui.table.row>
    @empty
        <x-ui.table.empty-state title="Nenhuma movimentação encontrada"
            description="Não há movimentações de estoque com os filtros selecionados." icon="heroicon-o-arrow-path" />
    @endforelse
</x-ui.table.body>

{{-- Pagination --}}
<x-ui.table.pagination :paginator="$transactions" />
