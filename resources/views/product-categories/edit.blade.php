<x-layouts.module title="Editar Categoria de Produto">
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Categorias de Produto', 'href' => route('product-categories.index')],
            ['label' => $category->name],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    <x-slot:header>
        Editando: {{ $category->name }}
    </x-slot:header>

    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-categories.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    <form action="{{ route('product-categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="max-w-2xl">
            <x-spire::card>
                <div class="space-y-4">
                    <x-spire::select name="product_line_id" label="Linha de Produto"
                        placeholder="Selecione a linha de produto" :value="old('product_line_id', (string) $category->product_line_id)" :options="$productLines
                            ->map(fn($pl) => ['value' => (string) $pl->id, 'label' => $pl->name])
                            ->toArray()" required />
                    @error('product_line_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    <x-spire::input type="text" name="name" label="Nome"
                        placeholder="Ex: TV, Geladeira, Notebook" :value="old('name', $category->name)" :error="$errors->first('name')" required
                        autofocus />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Descrição
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Descrição opcional da categoria...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <x-spire::button type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Salvar Alterações
                    </x-spire::button>
                    <x-spire::button type="button" variant="ghost" onclick="window.history.back()">
                        Cancelar
                    </x-spire::button>
                </div>
            </x-spire::card>
        </div>
    </form>
</x-layouts.module>
