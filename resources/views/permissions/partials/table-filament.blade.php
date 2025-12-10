{{-- Partial para requisições AJAX da tabela Filament-style --}}
{{-- Este partial retorna a tabela completa (thead + tbody) + paginação para substituir .fi-ta-content --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="Permissão" sortable sortField="name" data-column="permission" />
            <x-ui.table.column label="Grupo" sortable sortField="group" data-column="group" align="center" />
            <x-ui.table.column label="Perfis" data-column="roles" align="center" />
            <x-ui.table.column label="Usuários" data-column="users" align="center" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($permissions as $permission)
                <x-ui.table.row :record="$permission" :selectable="true" :clickable="true">
                    {{-- Permission Info --}}
                    <x-ui.table.cell data-column="permission">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-linear-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $permission->name }}</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 font-mono">{{ $permission->slug }}
                                </p>
                            </div>
                        </div>
                    </x-ui.table.cell>

                    {{-- Group --}}
                    <x-ui.table.cell data-column="group" align="center">
                        @if ($permission->group)
                            <x-spire::badge variant="secondary">
                                {{ ucfirst($permission->group) }}
                            </x-spire::badge>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Roles Count --}}
                    <x-ui.table.cell data-column="roles" align="center">
                        <x-spire::badge variant="info">
                            {{ $permission->roles_count ?? 0 }}
                        </x-spire::badge>
                    </x-ui.table.cell>

                    {{-- Users Count --}}
                    <x-ui.table.cell data-column="users" align="center">
                        <x-spire::badge variant="secondary">
                            {{ $permission->users_count ?? 0 }}
                        </x-spire::badge>
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        @can('view', $permission)
                            <x-ui.table.action :href="route('permissions.show', $permission)" tooltip="Visualizar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        @endcan
                        @can('update', $permission)
                            <x-ui.table.action :href="route('permissions.edit', $permission)" tooltip="Editar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        @endcan
                        @can('delete', $permission)
                            <x-ui.table.action color="danger" tooltip="Excluir"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                                onclick="if(confirm('Tem certeza que deseja excluir esta permissão?')) { document.getElementById('delete-permission-{{ $permission->id }}').submit(); }" />
                            <form id="delete-permission-{{ $permission->id }}"
                                action="{{ route('permissions.destroy', $permission) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcan
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhuma permissão encontrada"
                    description="Não há permissões cadastradas ou que correspondam aos filtros.">
                    <x-slot:action>
                        @can('create', App\Models\Permission::class)
                            <x-spire::button href="{{ route('permissions.create') }}">
                                Criar primeira permissão
                            </x-spire::button>
                        @endcan
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination --}}
@if ($permissions->hasPages())
    <x-ui.table.pagination :paginator="$permissions" />
@endif
