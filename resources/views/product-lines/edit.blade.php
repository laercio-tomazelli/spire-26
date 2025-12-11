<x-layouts.module title="Editar Linha de Produto">
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Linhas de Produto', 'href' => route('product-lines.index')],
            ['label' => $productLine->name],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    <x-slot:header>
        Editando: {{ $productLine->name }}
    </x-slot:header>

    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-lines.index') }}" variant="ghost">
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

    <form action="{{ route('product-lines.update', $productLine) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="max-w-2xl">
            <x-spire::card>
                <div class="space-y-4">
                    <x-spire::input type="text" name="name" label="Nome" placeholder="Ex: Linha Branca"
                        :value="old('name', $productLine->name)" :error="$errors->first('name')" required autofocus />

                    <x-spire::textarea name="description" label="Descrição" rows="3"
                        placeholder="Descrição opcional da linha de produto..." :value="old('description', $productLine->description)" :error="$errors->first('description')" />
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
