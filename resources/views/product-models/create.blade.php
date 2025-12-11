<x-layouts.module title="Novo Modelo de Produto">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Modelos de Produto', 'href' => route('product-models.index')],
            ['label' => 'Novo Modelo'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Preencha os dados para criar um novo modelo de produto
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('product-models.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('product-models.store') }}" method="POST" id="product-model-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::input type="text" name="model_code" label="Código do Modelo"
                            placeholder="Ex: RF450" :value="old('model_code')" :error="$errors->first('model_code')" required />

                        <x-spire::input type="text" name="model_name" label="Nome do Modelo"
                            placeholder="Ex: Geladeira Frost Free 450L" :value="old('model_name')" :error="$errors->first('model_name')" />

                        <x-spire::input type="text" name="manufacturer_model" label="Modelo do Fabricante"
                            placeholder="Ex: RF450-FFBR" :value="old('manufacturer_model')" :error="$errors->first('manufacturer_model')" />

                        <x-spire::input type="text" name="ean" label="EAN" placeholder="Ex: 7890000000123"
                            :value="old('ean')" :error="$errors->first('ean')" maxlength="20" />
                    </div>
                </x-spire::card>

                {{-- Classificação --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Classificação</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::select name="brand_id" label="Marca" placeholder="Selecione a marca"
                            :value="old('brand_id')" :options="$brands
                                ->map(fn($b) => ['value' => (string) $b->id, 'label' => $b->name])
                                ->toArray()" required />
                        @error('brand_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <x-spire::select name="product_category_id" label="Categoria"
                            placeholder="Selecione a categoria" :value="old('product_category_id')" :options="$categories
                                ->map(fn($c) => ['value' => (string) $c->id, 'label' => $c->name])
                                ->toArray()" />
                        @error('product_category_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </x-spire::card>

                {{-- Garantia e Datas --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Garantia e Datas</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::input type="number" name="warranty_months" label="Garantia (meses)" placeholder="12"
                            :value="old('warranty_months', 12)" :error="$errors->first('warranty_months')" min="0" max="999" />

                        <x-spire::input type="number" name="promotional_warranty_months"
                            label="Garantia Promocional (meses)" placeholder="24" :value="old('promotional_warranty_months')" :error="$errors->first('promotional_warranty_months')"
                            min="0" max="999" />

                        <x-spire::input type="date" name="release_date" label="Data de Lançamento" :value="old('release_date')"
                            :error="$errors->first('release_date')" />

                        <x-spire::input type="date" name="end_of_life_date" label="Data de Fim de Vida"
                            :value="old('end_of_life_date')" :error="$errors->first('end_of_life_date')" />
                    </div>
                </x-spire::card>

                {{-- Observações --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações</h2>

                    <x-spire::textarea name="observations" rows="4"
                        placeholder="Observações adicionais sobre o modelo..." :value="old('observations')" :error="$errors->first('observations')" />
                </x-spire::card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>

                    <x-spire::checkbox name="is_active" label="Ativo" hint="Modelos inativos não aparecem para seleção"
                        size="lg" :checked="old('is_active', true)" />
                </x-spire::card>

                {{-- Ações --}}
                <x-spire::card>
                    <div class="flex flex-col gap-3">
                        <x-spire::button type="submit" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Criar Modelo
                        </x-spire::button>

                        <x-spire::button type="button" variant="ghost" class="w-full" onclick="window.history.back()">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>
            </div>
        </div>
    </form>
</x-layouts.module>
