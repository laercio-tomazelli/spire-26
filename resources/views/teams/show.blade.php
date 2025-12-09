<x-layouts.module title="Detalhes do Time">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Times', 'href' => route('teams.index')],
            ['label' => $team->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        {{ $team->name }}
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('update', $team)
            <x-spire::button href="{{ route('teams.edit', $team) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </x-spire::button>
        @endcan
    </x-slot:headerActions>

    <div class="space-y-6">
        {{-- Basic Info --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Time</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $team->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $team->slug }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1">
                        @if ($team->is_active)
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                Ativo
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                Inativo
                            </span>
                        @endif
                    </dd>
                </div>
                @if ($team->tenant)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $team->tenant->name }}</dd>
                    </div>
                @endif
                @if ($team->description)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $team->description }}</dd>
                    </div>
                @endif
            </dl>
        </x-spire::card>

        {{-- Team Members --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Membros do Time
                <span class="text-sm font-normal text-gray-500">({{ $team->users->count() }})</span>
            </h2>
            @if ($team->users->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($team->users as $user)
                        <div class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <x-spire::avatar size="sm" :name="$user->name" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $user->name }}
                                    @if ($user->pivot->is_leader)
                                        <span
                                            class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            Líder
                                        </span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                    Nenhum membro no time.
                </p>
            @endif
        </x-spire::card>

        {{-- Roles --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Perfis Atribuídos
                <span class="text-sm font-normal text-gray-500">({{ $team->roles->count() }})</span>
            </h2>
            @if ($team->roles->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($team->roles as $role)
                        <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $role->name }}
                                    </p>
                                    <p class="text-xs text-purple-600 dark:text-purple-400">
                                        {{ $role->permissions->count() }} permissões
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                    Nenhum perfil atribuído.
                </p>
            @endif
        </x-spire::card>

        {{-- Permissions Summary --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Resumo de Permissões</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Todas as permissões que os membros deste time
                possuem</p>
            @php
                $allPermissions = $team->getAllPermissions()->groupBy('group');
            @endphp

            @if ($allPermissions->isNotEmpty())
                @foreach ($allPermissions as $group => $groupPermissions)
                    <div class="mb-4 last:mb-0">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 capitalize">
                            {{ $group ?: 'Geral' }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($groupPermissions as $permission)
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                    Nenhuma permissão atribuída.
                </p>
            @endif
        </x-spire::card>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <x-spire::button variant="ghost" href="{{ route('teams.index') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar
            </x-spire::button>

            @can('delete', $team)
                <form action="{{ route('teams.destroy', $team) }}" method="POST"
                    onsubmit="return confirm('Tem certeza que deseja excluir este time?')">
                    @csrf
                    @method('DELETE')
                    <x-spire::button type="submit" variant="danger">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Excluir Time
                    </x-spire::button>
                </form>
            @endcan
        </div>
    </div>
</x-layouts.module>
