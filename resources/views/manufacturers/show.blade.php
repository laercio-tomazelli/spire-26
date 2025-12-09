<x-layouts.module title="Fabricante - {{ $manufacturer->name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Fabricantes', 'href' => route('manufacturers.index')],
            ['label' => $manufacturer->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-3">
            @if ($manufacturer->logo_url)
                <img src="{{ $manufacturer->logo_url }}" alt="{{ $manufacturer->name }}"
                    class="w-10 h-10 rounded-lg object-cover">
            @else
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                        {{ mb_strtoupper(mb_substr($manufacturer->name, 0, 2)) }}
                    </span>
                </div>
            @endif
            <span>{{ $manufacturer->name }}</span>
            <span
                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $manufacturer->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
                {{ $manufacturer->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('manufacturers.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>

        @can('update', $manufacturer)
            <x-spire::button href="{{ route('manufacturers.edit', $manufacturer) }}">
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
            {{-- Dados da Empresa --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados do Fabricante</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $manufacturer->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CNPJ</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $manufacturer->document ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if ($manufacturer->email)
                                <a href="mailto:{{ $manufacturer->email }}"
                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ $manufacturer->email }}
                                </a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $manufacturer->phone ?: '-' }}
                        </dd>
                    </div>

                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if ($manufacturer->website)
                                <a href="{{ $manufacturer->website }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center gap-1">
                                    {{ $manufacturer->website }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Marcas --}}
            <x-spire::card>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Marcas</h2>
                    {{-- @can('create', \App\Models\Brand::class)
                        <x-spire::button href="{{ route('brands.create', ['manufacturer_id' => $manufacturer->id]) }}"
                            size="sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Nova Marca
                        </x-spire::button>
                    @endcan --}}
                </div>

                @if ($manufacturer->brands->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Nenhuma marca cadastrada para este fabricante.
                        </p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($manufacturer->brands as $brand)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    @if ($brand->logo_url)
                                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                                            class="w-8 h-8 rounded object-cover">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                                {{ mb_strtoupper(mb_substr($brand->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $brand->name }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded {{ $brand->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $brand->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-spire::card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Tenant --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vínculo</h2>

                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $manufacturer->tenant->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Tenant vinculado
                        </p>
                    </div>
                </div>
            </x-spire::card>

            {{-- Estatísticas --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estatísticas</h2>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Marcas</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $manufacturer->brands_count ?? $manufacturer->brands->count() }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Marcas Ativas</span>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                            {{ $manufacturer->brands->where('is_active', true)->count() }}
                        </span>
                    </div>
                </div>
            </x-spire::card>

            {{-- Metadados --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $manufacturer->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Atualizado em</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $manufacturer->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
