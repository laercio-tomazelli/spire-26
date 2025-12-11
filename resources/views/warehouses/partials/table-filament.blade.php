{{-- Partial para requisições AJAX da tabela Filament-style --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
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

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="Código" sortable sortField="code" data-column="code" />
            <x-ui.table.column label="Nome" sortable sortField="name" data-column="name" />
            <x-ui.table.column label="Tipo" sortable sortField="type" data-column="type" align="center" />
            <x-ui.table.column label="Localização" data-column="location" />
            <x-ui.table.column label="Parceiro" data-column="partner" />
            <x-ui.table.column label="Itens" data-column="items" align="center" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($warehouses as $warehouse)
                <x-ui.table.row :record="$warehouse" :selectable="true" :clickable="true">
                    {{-- Code --}}
                    <x-ui.table.cell data-column="code">
                        <span class="font-mono text-sm font-medium text-gray-900 dark:text-white">
                            {{ $warehouse->code }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Name --}}
                    <x-ui.table.cell data-column="name">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $warehouse->name }}
                            </p>
                            @if ($warehouse->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($warehouse->description, 50) }}
                                </p>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Type --}}
                    <x-ui.table.cell data-column="type" align="center">
                        <x-spire::badge :variant="$typeVariants[$warehouse->type] ?? 'secondary'">
                            {{ $typeLabels[$warehouse->type] ?? $warehouse->type }}
                        </x-spire::badge>
                    </x-ui.table.cell>

                    {{-- Location --}}
                    <x-ui.table.cell data-column="location">
                        @if ($warehouse->location)
                            <span class="text-gray-700 dark:text-gray-300">{{ $warehouse->location }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Partner --}}
                    <x-ui.table.cell data-column="partner">
                        @if ($warehouse->partner)
                            <span class="text-gray-700 dark:text-gray-300">{{ $warehouse->partner->trade_name }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Items Count --}}
                    <x-ui.table.cell data-column="items" align="center">
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $warehouse->inventory_items_count ?? 0 }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        @can('view', $warehouse)
                            <x-ui.table.action :href="route('warehouses.show', $warehouse)" tooltip="Visualizar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        @endcan
                        @can('update', $warehouse)
                            <x-ui.table.action :href="route('warehouses.edit', $warehouse)" tooltip="Editar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        @endcan
                        @can('delete', $warehouse)
                            <x-ui.table.action color="danger" tooltip="Excluir"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                                onclick="if(confirm('Tem certeza que deseja excluir este depósito?')) { document.getElementById('delete-warehouse-{{ $warehouse->id }}').submit(); }" />
                            <form id="delete-warehouse-{{ $warehouse->id }}"
                                action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcan
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhum depósito encontrado"
                    description="Não há depósitos cadastrados ou que correspondam aos filtros.">
                    <x-slot:action>
                        @can('create', App\Models\Warehouse::class)
                            <x-spire::button href="{{ route('warehouses.create') }}">
                                Criar primeiro depósito
                            </x-spire::button>
                        @endcan
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination Footer --}}
@if ($warehouses->hasPages())
    <x-ui.table.pagination :paginator="$warehouses" />
@endif
