{{-- Partial para requisições AJAX da tabela de Roles --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="Perfil" sortable sortField="name" data-column="role" />
            <x-ui.table.column label="Permissões" data-column="permissions" align="center" />
            <x-ui.table.column label="Usuários" data-column="users" align="center" />
            <x-ui.table.column label="Tipo" data-column="type" align="center" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($roles as $role)
                <x-ui.table.row :record="$role" :selectable="true" :clickable="true">
                    {{-- Role Info --}}
                    <x-ui.table.cell data-column="role">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-linear-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $role->name }}</p>
                                @if ($role->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $role->description }}</p>
                                @else
                                    <p class="text-sm text-gray-400 dark:text-gray-500 font-mono">{{ $role->slug }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </x-ui.table.cell>

                    {{-- Permissions Count --}}
                    <x-ui.table.cell data-column="permissions" class="text-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $role->permissions_count ?? 0 }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Users Count --}}
                    <x-ui.table.cell data-column="users" class="text-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            {{ $role->users_count ?? 0 }}
                        </span>
                    </x-ui.table.cell>

                    {{-- Type --}}
                    <x-ui.table.cell data-column="type">
                        @if ($role->is_system)
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Sistema
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                        clip-rule="evenodd" />
                                </svg>
                                Personalizado
                            </span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        @can('view', $role)
                            <x-ui.table.action :href="route('roles.show', $role)" tooltip="Visualizar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        @endcan
                        @can('update', $role)
                            <x-ui.table.action :href="route('roles.edit', $role)" tooltip="Editar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        @endcan
                        @can('delete', $role)
                            <x-ui.table.action color="danger" tooltip="Excluir"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                                onclick="if(confirm('Tem certeza que deseja excluir este perfil?')) { document.getElementById('delete-role-{{ $role->id }}').submit(); }" />
                            <form id="delete-role-{{ $role->id }}" action="{{ route('roles.destroy', $role) }}"
                                method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcan
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhum perfil encontrado"
                    description="Não há perfis cadastrados ou que correspondam aos filtros.">
                    <x-slot:action>
                        @can('create', App\Models\Role::class)
                            <x-spire::button href="{{ route('roles.create') }}">
                                Criar primeiro perfil
                            </x-spire::button>
                        @endcan
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination --}}
@if ($roles->hasPages())
    <x-ui.table.pagination :paginator="$roles" />
@endif
