{{-- Partial para requisições AJAX da tabela Filament-style --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="false">
            <x-ui.table.column label="Código" sortable sortField="part_code" data-column="part_code" />
            <x-ui.table.column label="Peça" data-column="part" />
            <x-ui.table.column label="Depósito" data-column="warehouse" />
            <x-ui.table.column label="Disponível" sortable sortField="available_quantity" data-column="available"
                align="right" />
            <x-ui.table.column label="Reservado" sortable sortField="reserved_quantity" data-column="reserved"
                align="right" />
            <x-ui.table.column label="Defeituoso" sortable sortField="defective_quantity" data-column="defective"
                align="right" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($items as $item)
                <x-ui.table.row :record="$item" :selectable="false" :clickable="true">
                    {{-- Part Code --}}
                    <x-ui.table.cell data-column="part_code">
                        <span class="font-mono text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->part_code }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Part --}}
                    <x-ui.table.cell data-column="part">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $item->part?->description ?? '—' }}
                            </p>
                            @if ($item->part?->short_description)
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($item->part->short_description, 40) }}
                                </p>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Warehouse --}}
                    <x-ui.table.cell data-column="warehouse">
                        @if ($item->warehouse)
                            <div>
                                <p class="text-gray-900 dark:text-white">{{ $item->warehouse->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->warehouse->code }}</p>
                            </div>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Available Quantity --}}
                    <x-ui.table.cell data-column="available" align="right">
                        <span
                            class="font-medium {{ $item->available_quantity > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                            {{ number_format($item->available_quantity) }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Reserved Quantity --}}
                    <x-ui.table.cell data-column="reserved" align="right">
                        <span
                            class="font-medium {{ $item->reserved_quantity > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-400' }}">
                            {{ number_format($item->reserved_quantity) }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Defective Quantity --}}
                    <x-ui.table.cell data-column="defective" align="right">
                        <span
                            class="font-medium {{ $item->defective_quantity > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400' }}">
                            {{ number_format($item->defective_quantity) }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        <x-ui.table.action :href="route('inventory.show', $item)" tooltip="Visualizar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhum item de estoque encontrado"
                    description="Não há itens cadastrados ou que correspondam aos filtros." />
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination Footer --}}
@if ($items->hasPages())
    <x-ui.table.pagination :paginator="$items" />
@endif
