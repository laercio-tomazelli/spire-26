<x-layouts.module title="{{ $part->description }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Peças', 'href' => route('parts.index')],
            ['label' => $part->part_code],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-3">
            @if ($part->is_active)
                <x-spire::badge variant="success">Ativa</x-spire::badge>
            @else
                <x-spire::badge variant="danger">Inativa</x-spire::badge>
            @endif
            @if ($part->is_display)
                <x-spire::badge variant="info">Display</x-spire::badge>
            @endif
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <div class="flex items-center gap-2">
            @can('update', $part)
                <form action="{{ route('parts.toggle-active', $part) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <x-spire::button type="submit" variant="outline" size="sm">
                        @if ($part->is_active)
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Desativar
                        @else
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Ativar
                        @endif
                    </x-spire::button>
                </form>

                <x-spire::button href="{{ route('parts.edit', $part) }}" variant="outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </x-spire::button>
            @endcan

            @can('delete', $part)
                <form action="{{ route('parts.destroy', $part) }}" method="POST" class="inline"
                    onsubmit="return confirm('Tem certeza que deseja excluir esta peça?')">
                    @csrf
                    @method('DELETE')
                    <x-spire::button type="submit" variant="danger">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Excluir
                    </x-spire::button>
                </form>
            @endcan
        </div>
    </x-slot:headerActions>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Dados Básicos --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Código</dt>
                        <dd class="mt-1 text-sm font-mono font-medium text-gray-900 dark:text-white">
                            {{ $part->part_code }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Unidade</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->unit }}
                        </dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Descrição</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->description }}
                        </dd>
                    </div>
                    @if ($part->short_description)
                        <div class="md:col-span-2">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Descrição Curta</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $part->short_description }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            {{-- Códigos e Identificação --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Códigos e Identificação</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">EAN</dt>
                        <dd class="mt-1 text-sm font-mono font-medium text-gray-900 dark:text-white">
                            {{ $part->ean ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">EAN Embalagem</dt>
                        <dd class="mt-1 text-sm font-mono font-medium text-gray-900 dark:text-white">
                            {{ $part->ean_packaging ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Código do Fabricante</dt>
                        <dd class="mt-1 text-sm font-mono font-medium text-gray-900 dark:text-white">
                            {{ $part->manufacturer_code ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Localização</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->location ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Informações Fiscais --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Fiscais</h2>

                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">NCM</dt>
                        <dd class="mt-1 text-sm font-mono font-medium text-gray-900 dark:text-white">
                            {{ $part->ncm ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">CEST</dt>
                        <dd class="mt-1 text-sm font-mono font-medium text-gray-900 dark:text-white">
                            {{ $part->cest ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Origem</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            @if ($part->origin !== null)
                                {{ $part->origin }} -
                                @switch($part->origin)
                                    @case(0)
                                        Nacional
                                    @break

                                    @case(1)
                                        Estrangeira (import. direta)
                                    @break

                                    @case(2)
                                        Estrangeira (merc. interno)
                                    @break

                                    @default
                                        Código {{ $part->origin }}
                                @endswitch
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Preços e Estoque --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preços e Estoque</h2>

                <dl class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Preço de Custo</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            R$ {{ number_format($part->cost_price ?? 0, 2, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Preço de Venda</dt>
                        <dd class="mt-1 text-lg font-semibold text-blue-600 dark:text-blue-400">
                            R$ {{ number_format($part->price ?? 0, 2, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Estoque Mínimo</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->min_stock ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Estoque Máximo</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->max_stock ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Dimensões e Peso --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dimensões e Peso</h2>

                <dl class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Peso Líquido</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->net_weight ? number_format($part->net_weight, 3, ',', '.') . ' kg' : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Peso Bruto</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->gross_weight ? number_format($part->gross_weight, 3, ',', '.') . ' kg' : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Largura</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->width ? number_format($part->width, 2, ',', '.') . ' cm' : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Altura</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->height ? number_format($part->height, 2, ',', '.') . ' cm' : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Profundidade</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $part->depth ? number_format($part->depth, 2, ',', '.') . ' cm' : '-' }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Modelos de Produto --}}
            @if ($part->productModels->count() > 0)
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Modelos de Produto ({{ $part->productModels->count() }})
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Modelo
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Marca
                                    </th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Qtd.
                                    </th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Fornecido
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($part->productModels as $productModel)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('product-models.show', $productModel) }}"
                                                class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $productModel->display_name }}
                                            </a>
                                            <div class="text-sm text-gray-500">{{ $productModel->model_code }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $productModel->brand?->name ?? '-' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-center text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $productModel->pivot->quantity }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($productModel->pivot->is_provided)
                                                <x-spire::badge variant="success" size="sm">Sim</x-spire::badge>
                                            @else
                                                <x-spire::badge variant="warning" size="sm">Não</x-spire::badge>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-spire::card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Resumo --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo</h2>

                <dl class="space-y-3">
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Estoque Total</dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $part->total_stock ?? 0 }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Modelos Vinculados</dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $part->productModels->count() }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Informações --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">ID</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">#{{ $part->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            {{ $part->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Atualizado em</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            {{ $part->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    @if ($part->bling_id)
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">ID Bling</dt>
                            <dd class="font-mono font-medium text-gray-900 dark:text-white">
                                {{ $part->bling_id }}
                            </dd>
                        </div>
                    @endif
                    @if ($part->synced_at)
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Sincronizado em</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">
                                {{ $part->synced_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            {{-- Ações Rápidas --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações Rápidas</h2>

                <div class="space-y-2">
                    <x-spire::button href="{{ route('parts.index') }}" variant="ghost" class="w-full justify-start">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Ver todas as peças
                    </x-spire::button>

                    @can('create', App\Models\Part::class)
                        <x-spire::button href="{{ route('parts.create') }}" variant="ghost"
                            class="w-full justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Criar nova peça
                        </x-spire::button>
                    @endcan
                </div>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
