<x-layouts.module title="{{ $category->name }}">
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Categorias de Produto', 'href' => route('product-categories.index')],
            ['label' => $category->name],
        ]" />
    </x-slot:breadcrumbs>

    <x-slot:header>
        <div class="flex items-center gap-3">
            <div
                class="w-12 h-12 rounded-lg bg-linear-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                {{ substr($category->name, 0, 2) }}
            </div>
            {{ $category->name }}
        </div>
    </x-slot:header>

    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-categories.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
        <x-spire::button href="{{ route('product-categories.edit', $category) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Editar
        </x-spire::button>
    </x-slot:headerActions>

    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Nome</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Linha de Produto</dt>
                        <dd>
                            @if ($category->productLine)
                                <a href="{{ route('product-lines.show', $category->productLine) }}" class="inline-flex">
                                    <x-spire::badge variant="info">{{ $category->productLine->name }}</x-spire::badge>
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    @if ($category->description)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Descrição</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $category->description }}</dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            @if ($category->productModels->count() > 0)
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Modelos de Produto
                        <span
                            class="text-sm font-normal text-gray-500">({{ $category->productModels->count() }})</span>
                    </h2>

                    <div class="space-y-2">
                        @foreach ($category->productModels->take(10) as $model)
                            <a href="{{ route('product-models.show', $model) }}"
                                class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $model->model_name ?? $model->model_code }}</p>
                                    <p class="text-sm text-gray-500 font-mono">{{ $model->model_code }}</p>
                                </div>
                                @php $status = \App\Enums\Status::fromBool($model->is_active) @endphp
                                <x-spire::badge :variant="$status->badgeVariant()">
                                    {{ $status->label() }}
                                </x-spire::badge>
                            </a>
                        @endforeach

                        @if ($category->productModels->count() > 10)
                            <p class="text-sm text-gray-500 text-center pt-2">
                                E mais {{ $category->productModels->count() - 10 }} modelos...
                            </p>
                        @endif
                    </div>
                </x-spire::card>
            @endif
        </div>

        <div class="space-y-6">
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detalhes</h2>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">ID</dt>
                        <dd class="font-mono text-gray-900 dark:text-white">#{{ $category->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $category->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Atualizado em</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $category->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
