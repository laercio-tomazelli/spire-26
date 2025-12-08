<x-layouts.module title="{{ $user->name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Usuários', 'href' => route('users.index')],
            ['label' => $user->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-4">
            <x-spire::avatar size="lg" :name="$user->name" />
            <div class="flex items-center gap-3">
                <span>{{ $user->email }}</span>
                @php $status = \App\Enums\Status::fromBool($user->is_active) @endphp
                <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                    {{ $status->label() }}
                </x-spire::badge>
            </div>
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('update', $user)
            <x-spire::button href="{{ route('users.edit', $user) }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </x-spire::button>
        @endcan
        <x-spire::button href="{{ route('users.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Dados Básicos --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome Completo</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">
                            <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:underline">
                                {{ $user->email }}
                            </a>
                        </dd>
                    </div>
                    @if ($user->username)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuário</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">{{ $user->username }}</dd>
                        </div>
                    @endif
                    @if ($user->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">{{ $user->phone }}</dd>
                        </div>
                    @endif
                    @if ($user->mobile)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Celular</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">{{ $user->mobile }}</dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            {{-- Vínculo --}}
            @if ($user->partner || $user->manufacturer || $user->tenant)
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vínculo</h2>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if ($user->partner)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Posto Autorizado</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">
                                    {{ $user->partner->code }} - {{ $user->partner->trade_name }}
                                </dd>
                            </div>
                            @if ($user->is_partner_admin)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Função</dt>
                                    <dd class="mt-1">
                                        <x-spire::badge variant="warning">Administrador do Posto</x-spire::badge>
                                    </dd>
                                </div>
                            @endif
                        @endif

                        @if ($user->manufacturer)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fabricante</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $user->manufacturer->name }}</dd>
                            </div>
                        @endif

                        @if ($user->tenant)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $user->tenant->name }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-spire::card>
            @endif

            {{-- Roles --}}
            @if ($user->roles->isNotEmpty())
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Papéis e Permissões</h2>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($user->roles as $role)
                            <x-spire::badge variant="info">{{ $role->name }}</x-spire::badge>
                        @endforeach
                    </div>
                </x-spire::card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Tipo e Status --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tipo e Status</h2>

                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Usuário</dt>
                        <dd class="mt-1">
                            <x-spire::badge :variant="$user->user_type->badgeVariant()" :icon="$user->user_type->icon()">
                                {{ $user->user_type->label() }}
                            </x-spire::badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            @php $status = \App\Enums\Status::fromBool($user->is_active) @endphp
                            <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                                {{ $status->label() }}
                            </x-spire::badge>
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Info --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Atualizado em</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if ($user->last_login_at)
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Último acesso</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $user->last_login_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                    @else
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Último acesso</dt>
                            <dd class="text-gray-400">Nunca acessou</dd>
                        </div>
                    @endif
                    @if ($user->createdByUser)
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Criado por</dt>
                            <dd class="text-gray-900 dark:text-white">
                                <a href="{{ route('users.show', $user->createdByUser) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $user->createdByUser->name }}
                                </a>
                            </dd>
                        </div>
                    @endif
                    @if ($user->email_verified_at)
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">E-mail verificado</dt>
                            <dd class="text-gray-900 dark:text-white">
                                {{ $user->email_verified_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            {{-- Actions --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações</h2>

                <div class="flex flex-col gap-3">
                    @can('update', $user)
                        <form action="{{ route('users.toggle-active', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-spire::button type="submit" variant="secondary" class="w-full">
                                @if ($user->is_active)
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    Desativar Usuário
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Ativar Usuário
                                @endif
                            </x-spire::button>
                        </form>
                    @endcan

                    @can('delete', $user)
                        <form action="{{ route('users.destroy', $user) }}" method="POST" x-data
                            @submit.prevent="if (confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')) $el.submit()">
                            @csrf
                            @method('DELETE')
                            <x-spire::button type="submit" variant="danger" class="w-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Excluir Usuário
                            </x-spire::button>
                        </form>
                    @endcan
                </div>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
