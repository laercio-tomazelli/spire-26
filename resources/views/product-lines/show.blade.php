<x-layouts.module title="{{ $productLine->name }}">
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Linhas de Produto', 'href' => route('product-lines.index')],
            ['label' => $productLine->name],
        ]" />
    </x-slot:breadcrumbs>

    <x-slot:header>
        <div class="flex items-center gap-3">
            <div
                class="w-12 h-12 rounded-lg bg-linear-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold">
                {{ substr($productLine->name, 0, 2) }}
            </div>
            {{ $productLine->name }}
        </div>
    </x-slot:header>

    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-lines.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
        <x-spire::button href="{{ route('product-lines.edit', $productLine) }}">
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
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $productLine->name }}</dd>
                    </div>
                    @if ($productLine->description)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Descrição</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $productLine->description }}</dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            @if ($productLine->categories->count() > 0)
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Categorias
                        <span class="text-sm font-normal text-gray-500">({{ $productLine->categories->count() }})</span>
                    </h2>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($productLine->categories as $category)
                            <a href="{{ route('product-categories.show', $category) }}"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                {{ $category->name }}
                            </a>
                        @endforeach
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
                        <dd class="font-mono text-gray-900 dark:text-white">#{{ $productLine->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $productLine->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Atualizado em</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $productLine->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
