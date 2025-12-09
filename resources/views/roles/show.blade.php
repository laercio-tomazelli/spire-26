<x-layouts.module title="Perfil - {{ $role->name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Perfis', 'href' => route('roles.index')],
            ['label' => $role->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-lg bg-linear-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <span>{{ $role->name }}</span>
            @if ($role->is_system)
                <span
                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                    Sistema
                </span>
            @else
                <span
                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                    Personalizado
                </span>
            @endif
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('roles.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>

        @can('update', $role)
            <x-spire::button href="{{ route('roles.edit', $role) }}">
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
            {{-- Dados do Perfil --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados do Perfil</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $role->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                            {{ $role->slug }}
                        </dd>
                    </div>

                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $role->description ?: '-' }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Permissões --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Permissões
                    <span class="text-sm font-normal text-gray-500">({{ $role->permissions_count }})</span>
                </h2>

                @if ($permissionsByGroup->isEmpty())
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <p class="mt-2">Nenhuma permissão atribuída a este perfil.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($permissionsByGroup as $group => $groupPermissions)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <div
                                    class="bg-gray-50 dark:bg-gray-800 px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <span class="font-medium text-gray-900 dark:text-white capitalize">
                                        {{ $group ?? 'Geral' }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        ({{ $groupPermissions->count() }})
                                    </span>
                                </div>
                                <div class="p-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($groupPermissions as $permission)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-spire::card>

            {{-- Usuários --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Usuários com este Perfil
                    <span class="text-sm font-normal text-gray-500">({{ $role->users_count }})</span>
                </h2>

                @if ($role->users->isEmpty())
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="mt-2">Nenhum usuário com este perfil.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($role->users->take(10) as $user)
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
                                <a href="{{ route('users.show', $user) }}"
                                    class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    Ver
                                </a>
                            </div>
                        @endforeach

                        @if ($role->users->count() > 10)
                            <div class="py-3 text-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    E mais {{ $role->users->count() - 10 }} usuários...
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
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estatísticas</h2>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Permissões</span>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                            {{ $role->permissions_count }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Usuários</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $role->users_count }}
                        </span>
                    </div>
                </div>
            </x-spire::card>

            {{-- Metadados --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Tipo</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $role->is_system ? 'Sistema' : 'Personalizado' }}
                        </dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $role->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Atualizado em</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $role->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
