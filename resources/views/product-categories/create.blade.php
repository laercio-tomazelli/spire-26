<x-layouts.module title="Nova Categoria de Produto">
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Categorias de Produto', 'href' => route('product-categories.index')],
            ['label' => 'Nova Categoria'],
        ]" />
    </x-slot:breadcrumbs>

    <x-slot:header>
        Cadastre uma nova categoria de produto
    </x-slot:header>

    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-categories.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('product-categories.store') }}" method="POST">
        @csrf

        <div class="max-w-2xl">
            <x-spire::card>
                <div class="space-y-4">
                    <x-spire::select name="product_line_id" label="Linha de Produto"
                        placeholder="Selecione a linha de produto" :value="old('product_line_id')" :options="$productLines
                            ->map(fn($pl) => ['value' => (string) $pl->id, 'label' => $pl->name])
                            ->toArray()" required />
                    @error('product_line_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    <x-spire::input type="text" name="name" label="Nome"
                        placeholder="Ex: TV, Geladeira, Notebook" :value="old('name')" :error="$errors->first('name')" required
                        autofocus />

                    <x-spire::textarea name="description" label="Descrição" rows="3"
                        placeholder="Descrição opcional da categoria..." :value="old('description')" :error="$errors->first('description')" />
                </div>

                <div class="mt-6 flex gap-3">
                    <x-spire::button type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Criar Categoria
                    </x-spire::button>
                    <x-spire::button type="button" variant="ghost" onclick="window.history.back()">
                        Cancelar
                    </x-spire::button>
                </div>
            </x-spire::card>
        </div>
    </form>
</x-layouts.module>
