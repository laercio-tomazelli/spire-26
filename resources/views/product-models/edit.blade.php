<x-layouts.module title="Editar Modelo de Produto">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Modelos de Produto', 'href' => route('product-models.index')],
            ['label' => $productModel->display_name],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-lg bg-linear-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        @can('view', $productModel)
            <x-spire::button href="{{ route('product-models.show', $productModel) }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Visualizar
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

    <form action="{{ route('product-models.update', $productModel) }}" method="POST" id="product-model-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::input type="text" name="model_code" label="Código do Modelo"
                            placeholder="Ex: RF450" :value="old('model_code', $productModel->model_code)" :error="$errors->first('model_code')" required />

                        <x-spire::input type="text" name="model_name" label="Nome do Modelo"
                            placeholder="Ex: Geladeira Frost Free 450L" :value="old('model_name', $productModel->model_name)" :error="$errors->first('model_name')" />

                        <x-spire::input type="text" name="manufacturer_model" label="Modelo do Fabricante"
                            placeholder="Ex: RF450-FFBR" :value="old('manufacturer_model', $productModel->manufacturer_model)" :error="$errors->first('manufacturer_model')" />

                        <x-spire::input type="text" name="ean" label="EAN" placeholder="Ex: 7890000000123"
                            :value="old('ean', $productModel->ean)" :error="$errors->first('ean')" maxlength="20" />
                    </div>
                </x-spire::card>

                {{-- Classificação --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Classificação</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::select name="brand_id" label="Marca" placeholder="Selecione a marca"
                            :value="old('brand_id', (string) $productModel->brand_id)" :options="$brands
                                ->map(fn($b) => ['value' => (string) $b->id, 'label' => $b->name])
                                ->toArray()" required />
                        @error('brand_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <x-spire::select name="product_category_id" label="Categoria"
                            placeholder="Selecione a categoria" :value="old('product_category_id', (string) $productModel->product_category_id)" :options="$categories
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
                            :value="old('warranty_months', $productModel->warranty_months)" :error="$errors->first('warranty_months')" min="0" max="999" />

                        <x-spire::input type="number" name="promotional_warranty_months"
                            label="Garantia Promocional (meses)" placeholder="24" :value="old('promotional_warranty_months', $productModel->promotional_warranty_months)" :error="$errors->first('promotional_warranty_months')"
                            min="0" max="999" />

                        <x-spire::input type="date" name="release_date" label="Data de Lançamento" :value="old('release_date', $productModel->release_date?->format('Y-m-d'))"
                            :error="$errors->first('release_date')" />

                        <x-spire::input type="date" name="end_of_life_date" label="Data de Fim de Vida"
                            :value="old('end_of_life_date', $productModel->end_of_life_date?->format('Y-m-d'))" :error="$errors->first('end_of_life_date')" />
                    </div>
                </x-spire::card>

                {{-- Observações --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações</h2>

                    <div>
                        <textarea name="observations" rows="4"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Observações adicionais sobre o modelo...">{{ old('observations', $productModel->observations) }}</textarea>
                        @error('observations')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </x-spire::card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                            class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            {{ old('is_active', $productModel->is_active) ? 'checked' : '' }}>
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">Ativo</span>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Modelos inativos não aparecem para seleção
                            </p>
                        </div>
                    </label>
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
                                {{ $productModel->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Atualizado em</dt>
                            <dd class="text-gray-900 dark:text-white">
                                {{ $productModel->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </x-spire::card>

                {{-- Ações --}}
                <x-spire::card>
                    <div class="flex flex-col gap-3">
                        <x-spire::button type="submit" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Salvar Alterações
                        </x-spire::button>

                        <x-spire::button type="button" variant="ghost" class="w-full"
                            onclick="window.history.back()">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>
            </div>
        </div>
    </form>
</x-layouts.module>
