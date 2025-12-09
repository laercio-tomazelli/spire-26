<x-layouts.module title="Permissão - {{ $permission->name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Permissões', 'href' => route('permissions.index')],
            ['label' => $permission->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-lg bg-linear-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <span>{{ $permission->name }}</span>
            @if ($permission->group)
                <span
                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 capitalize">
                    {{ $permission->group }}
                </span>
            @endif
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('permissions.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>

        @can('update', $permission)
            <x-spire::button href="{{ route('permissions.edit', $permission) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </x-spire::button>
        @endcan
    </x-slot:headerActions>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Dados da Permissão --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados da Permissão</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $permission->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                            {{ $permission->slug }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Grupo</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white capitalize">
                            {{ $permission->group ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $permission->description ?: '-' }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Perfis com esta Permissão --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Perfis com esta Permissão
                    <span class="text-sm font-normal text-gray-500">({{ $permission->roles_count }})</span>
                </h2>

                @if ($permission->roles->isEmpty())
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <p class="mt-2">Nenhum perfil possui esta permissão.</p>
                    </div>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach ($permission->roles as $role)
                            <a href="{{ route('roles.show', $role) }}"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-purple-100 text-purple-800 hover:bg-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:hover:bg-purple-900/50 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                {{ $role->name }}
                                @if ($role->is_system)
                                    <span class="ml-1 text-xs opacity-60">(sistema)</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </x-spire::card>

            {{-- Usuários com Permissão Direta --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Usuários com Permissão Direta
                    <span class="text-sm font-normal text-gray-500">({{ $permission->users_count }})</span>
                </h2>

                @if ($permission->users->isEmpty())
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="mt-2">Nenhum usuário possui esta permissão diretamente.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($permission->users->take(10) as $user)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <x-spire::avatar :name="$user->name" size="sm" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if ($user->pivot->granted)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            Concedida
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            Revogada
                                        </span>
                                    @endif
                                    <a href="{{ route('users.show', $user) }}"
                                        class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        @if ($permission->users->count() > 10)
                            <div class="py-3 text-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    E mais {{ $permission->users->count() - 10 }} usuários...
                                </span>
                            </div>
                        @endif
                    </div>
                @endif
            </x-spire::card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Estatísticas --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Uso</h2>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Perfis</span>
                        <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">
                            {{ $permission->roles_count }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Usuários diretos</span>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                            {{ $permission->users_count }}
                        </span>
                    </div>
                </div>
            </x-spire::card>

            {{-- Metadados --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Criada em</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $permission->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Atualizada em</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $permission->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
