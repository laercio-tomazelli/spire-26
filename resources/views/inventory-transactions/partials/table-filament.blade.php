{{-- Partial para requisições AJAX da tabela Filament-style --}}
{{-- Este partial retorna a tabela completa (thead + tbody) + paginação para substituir .fi-ta-content --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="false">
            <x-ui.table.column label="Data" sortable sortField="created_at" data-column="date" />
            <x-ui.table.column label="Peça" sortable sortField="part_code" data-column="part" />
            <x-ui.table.column label="Depósito" data-column="warehouse" />
            <x-ui.table.column label="Quantidade" sortable sortField="quantity" data-column="quantity" align="center" />
            <x-ui.table.column label="Documento" data-column="document" />
            <x-ui.table.column label="Usuário" data-column="user" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse($transactions as $transaction)
                <x-ui.table.row :record="$transaction" :selectable="false" :clickable="true">
                    {{-- Date --}}
                    <x-ui.table.cell data-column="date">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $transaction->created_at->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $transaction->created_at->format('H:i') }}
                        </div>
                    </x-ui.table.cell>

                    {{-- Part --}}
                    <x-ui.table.cell data-column="part">
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

                    {{-- Warehouse --}}
                    <x-ui.table.cell data-column="warehouse">
                        @if ($transaction->warehouse)
                            <a href="{{ route('warehouses.show', $transaction->warehouse) }}"
                                class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-200">
                                <div class="font-medium">{{ $transaction->warehouse->code }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction->warehouse->name }}</div>
                            </a>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Quantity --}}
                    <x-ui.table.cell data-column="quantity" align="center">
                        @php
                            $isPositive = $transaction->quantity > 0;
                            $colorClass = $isPositive
                                ? 'text-green-600 dark:text-green-400'
                                : 'text-red-600 dark:text-red-400';
                            $bgClass = $isPositive
                                ? 'bg-green-50 dark:bg-green-900/20'
                                : 'bg-red-50 dark:bg-red-900/20';
                        @endphp
                        <span class="{{ $colorClass }} {{ $bgClass }} px-2 py-1 rounded-md text-sm font-bold">
                            {{ $isPositive ? '+' : '' }}{{ number_format($transaction->quantity, 0, ',', '.') }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Document --}}
                    <x-ui.table.cell data-column="document">
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

                    {{-- User --}}
                    <x-ui.table.cell data-column="user">
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

                    {{-- Actions --}}
                    <x-ui.table.cell data-column="actions">
                        <x-ui.table.actions>
                            <x-ui.table.action :href="route('inventory-transactions.show', $transaction)" tooltip="Ver detalhes"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        </x-ui.table.actions>
                    </x-ui.table.cell>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhuma movimentação encontrada"
                    description="Não há movimentações de estoque com os filtros selecionados." />
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination --}}
<x-ui.table.pagination :paginator="$transactions" />
