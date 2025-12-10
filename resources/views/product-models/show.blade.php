<x-layouts.module title="{{ $productModel->display_name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Modelos de Produto', 'href' => route('product-models.index')],
            ['label' => $productModel->display_name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-3">
            <div
                class="w-12 h-12 rounded-lg bg-linear-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                {{ $productModel->display_name }}
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400 font-mono ml-2">
                    {{ $productModel->model_code }}
                </span>
            </div>
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-models.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
        @can('update', $productModel)
            <x-spire::button href="{{ route('product-models.edit', $productModel) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </x-spire::button>
        @endcan
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    @if (session('error'))
        <x-spire::alert type="danger" class="mb-6">
            {{ session('error') }}
        </x-spire::alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Dados Básicos --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Código do Modelo</dt>
                        <dd class="font-mono text-gray-900 dark:text-white">{{ $productModel->model_code }}</dd>
                    </div>
                    @if ($productModel->model_name)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Nome do Modelo</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $productModel->model_name }}</dd>
                        </div>
                    @endif
                    @if ($productModel->manufacturer_model)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Modelo do Fabricante</dt>
                            <dd class="font-mono text-gray-900 dark:text-white">{{ $productModel->manufacturer_model }}
                            </dd>
                        </div>
                    @endif
                    @if ($productModel->ean)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">EAN</dt>
                            <dd class="font-mono text-gray-900 dark:text-white">{{ $productModel->ean }}</dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            {{-- Classificação --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Classificação</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Marca</dt>
                        <dd>
                            @if ($productModel->brand)
                                <x-spire::badge variant="info">{{ $productModel->brand->name }}</x-spire::badge>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Categoria</dt>
                        <dd>
                            @if ($productModel->category)
                                <x-spire::badge
                                    variant="secondary">{{ $productModel->category->name }}</x-spire::badge>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Garantia e Datas --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Garantia e Datas</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Garantia</dt>
                        <dd>
                            @if ($productModel->warranty_months)
                                <x-spire::badge variant="secondary">{{ $productModel->warranty_months }}
                                    meses</x-spire::badge>
                            @else
                                <span class="text-gray-400">Não definida</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Garantia Promocional</dt>
                        <dd>
                            @if ($productModel->promotional_warranty_months)
                                <x-spire::badge variant="success">{{ $productModel->promotional_warranty_months }}
                                    meses</x-spire::badge>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Data de Lançamento</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $productModel->release_date?->format('d/m/Y') ?? '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Data de Fim de Vida</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $productModel->end_of_life_date?->format('d/m/Y') ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Observações --}}
            @if ($productModel->observations)
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações</h2>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $productModel->observations }}
                    </p>
                </x-spire::card>
            @endif

            {{-- Peças Compatíveis --}}
            @if ($productModel->parts->count() > 0)
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Peças Compatíveis
                        <span class="text-sm font-normal text-gray-500">({{ $productModel->parts->count() }})</span>
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b dark:border-gray-700">
                                    <th class="text-left py-2 px-3 font-medium text-gray-500 dark:text-gray-400">Peça
                                    </th>
                                    <th class="text-center py-2 px-3 font-medium text-gray-500 dark:text-gray-400">Qtd
                                    </th>
                                    <th class="text-center py-2 px-3 font-medium text-gray-500 dark:text-gray-400">
                                        Fornecida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productModel->parts as $part)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-2 px-3">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $part->description }}
                                            </div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $part->part_code }}</div>
                                        </td>
                                        <td class="py-2 px-3 text-center">{{ $part->pivot->quantity ?? 1 }}</td>
                                        <td class="py-2 px-3 text-center">
                                            @if ($part->pivot->is_provided ?? true)
                                                <x-spire::badge variant="success">Sim</x-spire::badge>
                                            @else
                                                <x-spire::badge variant="secondary">Não</x-spire::badge>
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
            {{-- Status --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>

                @php $status = \App\Enums\Status::fromBool($productModel->is_active) @endphp
                <div class="flex items-center gap-3">
                    <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()" size="lg">
                        {{ $status->label() }}
                    </x-spire::badge>

                    @can('update', $productModel)
                        <form action="{{ route('product-models.toggle-active', $productModel) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ $productModel->is_active ? 'Desativar' : 'Ativar' }}
                            </button>
                        </form>
                    @endcan
                </div>
            </x-spire::card>

            {{-- Informações --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">ID</dt>
                        <dd class="font-mono text-gray-900 dark:text-white">#{{ $productModel->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $productModel->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Atualizado em</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ $productModel->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Ações Rápidas --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações Rápidas</h2>

                <div class="flex flex-col gap-2">
                    @can('update', $productModel)
                        <x-spire::button href="{{ route('product-models.edit', $productModel) }}" variant="ghost"
                            class="w-full justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar modelo
                        </x-spire::button>
                    @endcan
                </div>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
