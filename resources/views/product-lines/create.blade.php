<x-layouts.module title="Nova Linha de Produto">
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Linhas de Produto', 'href' => route('product-lines.index')],
            ['label' => 'Nova Linha'],
        ]" />
    </x-slot:breadcrumbs>

    <x-slot:header>
        Cadastre uma nova linha de produto
    </x-slot:header>

    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-lines.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('product-lines.store') }}" method="POST">
        @csrf

        <div class="max-w-2xl">
            <x-spire::card>
                <div class="space-y-4">
                    <x-spire::input type="text" name="name" label="Nome" placeholder="Ex: Linha Branca"
                        :value="old('name')" :error="$errors->first('name')" required autofocus />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Descrição
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Descrição opcional da linha de produto...">{{ old('description') }}</textarea>
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
                        Criar Linha
                    </x-spire::button>
                    <x-spire::button type="button" variant="ghost" onclick="window.history.back()">
                        Cancelar
                    </x-spire::button>
                </div>
            </x-spire::card>
        </div>
    </form>
</x-layouts.module>
