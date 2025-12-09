{{-- Partial para requisições AJAX da tabela de Manufacturers --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="Fabricante" sortable sortField="name" data-column="manufacturer" />
            <x-ui.table.column label="Tenant" data-column="tenant" align="center" />
            <x-ui.table.column label="Contato" data-column="contact" align="center" />
            <x-ui.table.column label="Estatísticas" data-column="stats" align="center" />
            <x-ui.table.column label="Status" data-column="status" align="center" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($manufacturers as $manufacturer)
                <x-ui.table.row :record="$manufacturer" :selectable="true" :clickable="true">
                    {{-- Manufacturer Info --}}
                    <x-ui.table.cell data-column="manufacturer">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-linear-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($manufacturer->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $manufacturer->name }}</p>
                                @if ($manufacturer->document)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">
                                        {{ $manufacturer->document }}</p>
                                @endif
                            </div>
                        </div>
                    </x-ui.table.cell>

                    {{-- Tenant --}}
                    <x-ui.table.cell data-column="tenant" align="center">
                        @if ($manufacturer->tenant)
                            <span class="text-sm">{{ $manufacturer->tenant->name }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Contact --}}
                    <x-ui.table.cell data-column="contact" align="center">
                        <div class="text-sm">
                            @if ($manufacturer->email)
                                <p>
                                    <a href="mailto:{{ $manufacturer->email }}" class="text-blue-600 hover:underline">
                                        {{ $manufacturer->email }}
                                    </a>
                                </p>
                            @endif
                            @if ($manufacturer->phone)
                                <p class="text-gray-500 dark:text-gray-400">{{ $manufacturer->phone }}</p>
                            @endif
                            @if (!$manufacturer->email && !$manufacturer->phone)
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Stats --}}
                    <x-ui.table.cell data-column="stats" align="center">
                        <div class="flex items-center justify-center gap-4 text-sm">
                            <span class="inline-flex items-center gap-1" title="Marcas">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $manufacturer->brands_count ?? 0 }}
                            </span>
                            <span class="inline-flex items-center gap-1" title="Usuários">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                {{ $manufacturer->users_count ?? 0 }}
                            </span>
                        </div>
                    </x-ui.table.cell>

                    {{-- Status --}}
                    <x-ui.table.cell data-column="status" align="center">
                        @php $status = \App\Enums\Status::fromBool($manufacturer->is_active) @endphp
                        <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                            {{ $status->label() }}
                        </x-spire::badge>
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        <x-ui.table.action :href="route('manufacturers.show', $manufacturer)" tooltip="Visualizar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        <x-ui.table.action :href="route('manufacturers.edit', $manufacturer)" tooltip="Editar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        <x-ui.table.action color="danger" tooltip="Excluir"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                            onclick="if(confirm('Tem certeza que deseja excluir este fabricante?')) { document.getElementById('delete-manufacturer-{{ $manufacturer->id }}').submit(); }" />
                        <form id="delete-manufacturer-{{ $manufacturer->id }}"
                            action="{{ route('manufacturers.destroy', $manufacturer) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhum fabricante encontrado"
                    description="Não há fabricantes cadastrados ou que correspondam aos filtros.">
                    <x-slot:action>
                        <x-spire::button href="{{ route('manufacturers.create') }}">
                            Criar primeiro fabricante
                        </x-spire::button>
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination --}}
@if ($manufacturers->hasPages())
    <x-ui.table.pagination :paginator="$manufacturers" />
@endif
