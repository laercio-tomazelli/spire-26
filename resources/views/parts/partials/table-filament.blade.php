{{-- Partial para requisições AJAX da tabela Filament-style --}}
{{-- Este partial retorna a tabela completa (thead + tbody) + paginação para substituir .fi-ta-content --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="Código" sortable sortField="part_code" data-column="code" />
            <x-ui.table.column label="Descrição" sortable sortField="description" data-column="description" />
            <x-ui.table.column label="Unidade" data-column="unit" align="center" />
            <x-ui.table.column label="Preço" sortable sortField="price" data-column="price" align="right" />
            <x-ui.table.column label="Status" data-column="status" align="center" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($parts as $part)
                <x-ui.table.row :record="$part" :selectable="true" :clickable="true">
                    {{-- Code --}}
                    <x-ui.table.cell data-column="code">
                        <span class="font-mono text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->part_code }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Description --}}
                    <x-ui.table.cell data-column="description">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $part->description }}
                            </p>
                            @if ($part->short_description)
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($part->short_description, 50) }}
                                </p>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Unit --}}
                    <x-ui.table.cell data-column="unit" align="center">
                        <x-spire::badge variant="secondary">
                            {{ $part->unit }}
                        </x-spire::badge>
                    </x-ui.table.cell>

                    {{-- Price --}}
                    <x-ui.table.cell data-column="price" align="right">
                        <span class="font-medium text-gray-900 dark:text-white">
                            R$ {{ number_format($part->price ?? 0, 2, ',', '.') }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Status --}}
                    <x-ui.table.cell data-column="status" align="center">
                        @php $status = \App\Enums\Status::fromBool($part->is_active) @endphp
                        <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                            {{ $status->label() }}
                        </x-spire::badge>
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        @can('view', $part)
                            <x-ui.table.action :href="route('parts.show', $part)" tooltip="Visualizar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        @endcan
                        @can('update', $part)
                            <x-ui.table.action :href="route('parts.edit', $part)" tooltip="Editar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        @endcan
                        @can('delete', $part)
                            <x-ui.table.action color="danger" tooltip="Excluir"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                                onclick="if(confirm('Tem certeza que deseja excluir esta peça?')) { document.getElementById('delete-part-{{ $part->id }}').submit(); }" />
                            <form id="delete-part-{{ $part->id }}" action="{{ route('parts.destroy', $part) }}"
                                method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcan
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhuma peça encontrada"
                    description="Não há peças cadastradas ou que correspondam aos filtros.">
                    <x-slot:action>
                        @can('create', App\Models\Part::class)
                            <x-spire::button href="{{ route('parts.create') }}">
                                Criar primeira peça
                            </x-spire::button>
                        @endcan
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination Footer --}}
@if ($parts->hasPages())
    <x-ui.table.pagination :paginator="$parts" />
@endif
