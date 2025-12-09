{{-- Partial para requisições AJAX da tabela de Tenants --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="Tenant" sortable sortField="name" data-column="tenant" />
            <x-ui.table.column label="CNPJ" data-column="document" align="center" />
            <x-ui.table.column label="Contato" data-column="contact" align="center" />
            <x-ui.table.column label="Estatísticas" data-column="stats" align="center" />
            <x-ui.table.column label="Status" data-column="status" align="center" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($tenants as $tenant)
                <x-ui.table.row :record="$tenant" :selectable="true" :clickable="true">
                    {{-- Tenant Info --}}
                    <x-ui.table.cell data-column="tenant">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($tenant->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $tenant->name }}</p>
                                @if ($tenant->email)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $tenant->email }}</p>
                                @endif
                            </div>
                        </div>
                    </x-ui.table.cell>

                    {{-- Document --}}
                    <x-ui.table.cell data-column="document" align="center">
                        @if ($tenant->document)
                            <span class="font-mono text-sm">{{ $tenant->document }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Contact --}}
                    <x-ui.table.cell data-column="contact" align="center">
                        <div class="text-sm">
                            @if ($tenant->phone)
                                <p>{{ $tenant->phone }}</p>
                            @endif
                            @if ($tenant->city && $tenant->state)
                                <p class="text-gray-500 dark:text-gray-400">{{ $tenant->city }}/{{ $tenant->state }}</p>
                            @endif
                            @if (!$tenant->phone && !$tenant->city)
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Stats --}}
                    <x-ui.table.cell data-column="stats" align="center">
                        <div class="flex items-center justify-center gap-4 text-sm">
                            <span class="inline-flex items-center gap-1" title="Usuários">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                {{ $tenant->users_count ?? 0 }}
                            </span>
                            <span class="inline-flex items-center gap-1" title="Fabricantes">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $tenant->manufacturers_count ?? 0 }}
                            </span>
                        </div>
                    </x-ui.table.cell>

                    {{-- Status --}}
                    <x-ui.table.cell data-column="status" align="center">
                        @php $status = \App\Enums\Status::fromBool($tenant->is_active) @endphp
                        <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                            {{ $status->label() }}
                        </x-spire::badge>
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        <x-ui.table.action :href="route('tenants.show', $tenant)" tooltip="Visualizar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        <x-ui.table.action :href="route('tenants.edit', $tenant)" tooltip="Editar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        <x-ui.table.action color="danger" tooltip="Excluir"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                            onclick="if(confirm('Tem certeza que deseja excluir este tenant?')) { document.getElementById('delete-tenant-{{ $tenant->id }}').submit(); }" />
                        <form id="delete-tenant-{{ $tenant->id }}" action="{{ route('tenants.destroy', $tenant) }}"
                            method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhum tenant encontrado"
                    description="Não há tenants cadastrados ou que correspondam aos filtros.">
                    <x-slot:action>
                        <x-spire::button href="{{ route('tenants.create') }}">
                            Criar primeiro tenant
                        </x-spire::button>
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination --}}
@if ($tenants->hasPages())
    <x-ui.table.pagination :paginator="$tenants" />
@endif
