<x-layouts.module title="{{ $tenant->name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Tenants', 'href' => route('tenants.index')],
            ['label' => $tenant->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-4">
            <div
                class="w-12 h-12 rounded-lg bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($tenant->name, 0, 2)) }}
            </div>
            <div class="flex items-center gap-3">
                @if ($tenant->document)
                    <span class="font-mono text-sm text-gray-500">{{ $tenant->document }}</span>
                @endif
                @php $status = \App\Enums\Status::fromBool($tenant->is_active) @endphp
                <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                    {{ $status->label() }}
                </x-spire::badge>
            </div>
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        @can('update', $tenant)
            <x-spire::button href="{{ route('tenants.edit', $tenant) }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </x-spire::button>
        @endcan
        <x-spire::button href="{{ route('tenants.index') }}" variant="ghost">
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

    @if (session('error'))
        <x-spire::alert type="error" class="mb-6">
            {{ session('error') }}
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
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $tenant->name }}</dd>
                    </div>
                    @if ($tenant->document)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CNPJ</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white font-mono">{{ $tenant->document }}</dd>
                        </div>
                    @endif
                    @if ($tenant->email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">
                                <a href="mailto:{{ $tenant->email }}" class="text-blue-600 hover:underline">
                                    {{ $tenant->email }}
                                </a>
                            </dd>
                        </div>
                    @endif
                    @if ($tenant->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">{{ $tenant->phone }}</dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            {{-- Endereço --}}
            @if ($tenant->address || $tenant->city || $tenant->state)
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço</h2>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if ($tenant->address)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Endereço</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $tenant->address }}</dd>
                            </div>
                        @endif
                        @if ($tenant->city)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cidade</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $tenant->city }}</dd>
                            </div>
                        @endif
                        @if ($tenant->state)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $tenant->state }}</dd>
                            </div>
                        @endif
                        @if ($tenant->postal_code)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CEP</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $tenant->postal_code }}</dd>
                            </div>
                        @endif
                        @if ($tenant->country)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">País</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $tenant->country }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-spire::card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Estatísticas --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estatísticas</h2>

                <dl class="space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Usuários
                        </dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $tenant->users_count ?? 0 }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Fabricantes
                        </dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $tenant->manufacturers_count ?? 0 }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Parceiros
                        </dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $tenant->partners_count ?? 0 }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Timestamps --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">
                            {{ $tenant->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Última atualização</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">
                            {{ $tenant->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
