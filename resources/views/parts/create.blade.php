<x-layouts.module title="Nova Peça">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Peças', 'href' => route('parts.index')],
            ['label' => 'Nova Peça'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Preencha os dados para criar uma nova peça
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('parts.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('parts.store') }}" method="POST" id="part-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::input type="text" name="part_code" label="Código da Peça" placeholder="Ex: PRT-001"
                            :value="old('part_code')" :error="$errors->first('part_code')" required />

                        <x-spire::select name="unit" label="Unidade" :value="old('unit', 'UN')" :options="[
                            ['value' => 'UN', 'label' => 'UN - Unidade'],
                            ['value' => 'PC', 'label' => 'PC - Peça'],
                            ['value' => 'KIT', 'label' => 'KIT - Kit'],
                            ['value' => 'CJ', 'label' => 'CJ - Conjunto'],
                            ['value' => 'M', 'label' => 'M - Metro'],
                            ['value' => 'L', 'label' => 'L - Litro'],
                            ['value' => 'KG', 'label' => 'KG - Quilograma'],
                        ]" required />
                        @error('unit')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <div class="md:col-span-2">
                            <x-spire::input type="text" name="description" label="Descrição"
                                placeholder="Ex: Compressor 1/4HP 110V" :value="old('description')" :error="$errors->first('description')" required />
                        </div>

                        <div class="md:col-span-2">
                            <x-spire::input type="text" name="short_description" label="Descrição Curta"
                                placeholder="Ex: Compressor 1/4HP" :value="old('short_description')" :error="$errors->first('short_description')" />
                        </div>
                    </div>
                </x-spire::card>

                {{-- Códigos e Identificação --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Códigos e Identificação</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::input type="text" name="ean" label="EAN" placeholder="7890000000123"
                            :value="old('ean')" :error="$errors->first('ean')" maxlength="20" />

                        <x-spire::input type="text" name="ean_packaging" label="EAN Embalagem"
                            placeholder="7890000000456" :value="old('ean_packaging')" :error="$errors->first('ean_packaging')" maxlength="20" />

                        <x-spire::input type="text" name="manufacturer_code" label="Código do Fabricante"
                            placeholder="ABC-12345" :value="old('manufacturer_code')" :error="$errors->first('manufacturer_code')" />

                        <x-spire::input type="text" name="location" label="Localização no Estoque"
                            placeholder="Ex: A1-P2-G3" :value="old('location')" :error="$errors->first('location')" />
                    </div>
                </x-spire::card>

                {{-- Informações Fiscais --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Fiscais</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-spire::input type="text" name="ncm" label="NCM" placeholder="8418.69.99"
                            :value="old('ncm')" :error="$errors->first('ncm')" maxlength="15" />

                        <x-spire::input type="text" name="cest" label="CEST" placeholder="21.058.00"
                            :value="old('cest')" :error="$errors->first('cest')" maxlength="15" />

                        <x-spire::select name="origin" label="Origem" placeholder="Selecione" :value="old('origin')"
                            :options="[
                                ['value' => '0', 'label' => '0 - Nacional'],
                                ['value' => '1', 'label' => '1 - Estrangeira (import. direta)'],
                                ['value' => '2', 'label' => '2 - Estrangeira (merc. interno)'],
                                ['value' => '3', 'label' => '3 - Nacional (imp. > 40%)'],
                                ['value' => '4', 'label' => '4 - Nacional (prod. básico)'],
                                ['value' => '5', 'label' => '5 - Nacional (imp. < 40%)'],
                                ['value' => '6', 'label' => '6 - Estrangeira (sem similar)'],
                                ['value' => '7', 'label' => '7 - Estrangeira (com similar)'],
                                ['value' => '8', 'label' => '8 - Nacional (imp. > 70%)'],
                            ]" />
                        @error('origin')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </x-spire::card>

                {{-- Preços --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preços</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::input type="number" name="cost_price" label="Preço de Custo" placeholder="0.00"
                            :value="old('cost_price')" :error="$errors->first('cost_price')" step="0.01" min="0" />

                        <x-spire::input type="number" name="price" label="Preço de Venda" placeholder="0.00"
                            :value="old('price')" :error="$errors->first('price')" step="0.01" min="0" />
                    </div>
                </x-spire::card>

                {{-- Estoque --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estoque</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::input type="number" name="min_stock" label="Estoque Mínimo" placeholder="0"
                            :value="old('min_stock')" :error="$errors->first('min_stock')" min="0" />

                        <x-spire::input type="number" name="max_stock" label="Estoque Máximo" placeholder="0"
                            :value="old('max_stock')" :error="$errors->first('max_stock')" min="0" />
                    </div>
                </x-spire::card>

                {{-- Dimensões e Peso --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dimensões e Peso</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-spire::input type="number" name="net_weight" label="Peso Líquido (kg)" placeholder="0.000"
                            :value="old('net_weight')" :error="$errors->first('net_weight')" step="0.001" min="0" />

                        <x-spire::input type="number" name="gross_weight" label="Peso Bruto (kg)"
                            placeholder="0.000" :value="old('gross_weight')" :error="$errors->first('gross_weight')" step="0.001"
                            min="0" />

                        <div></div>

                        <x-spire::input type="number" name="width" label="Largura (cm)" placeholder="0.00"
                            :value="old('width')" :error="$errors->first('width')" step="0.01" min="0" />

                        <x-spire::input type="number" name="height" label="Altura (cm)" placeholder="0.00"
                            :value="old('height')" :error="$errors->first('height')" step="0.01" min="0" />

                        <x-spire::input type="number" name="depth" label="Profundidade (cm)" placeholder="0.00"
                            :value="old('depth')" :error="$errors->first('depth')" step="0.01" min="0" />
                    </div>
                </x-spire::card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>

                    <div class="space-y-4">
                        <x-spire::checkbox name="is_active" label="Ativa" size="lg"
                            hint="Peças inativas não aparecem para seleção" :checked="old('is_active', true)" />

                        <x-spire::checkbox name="is_display" label="É Display" size="lg"
                            hint="Marque se esta peça é para exposição" :checked="old('is_display', false)" />
                    </div>
                </x-spire::card>

                {{-- Ações --}}
                <x-spire::card>
                    <div class="flex flex-col gap-3">
                        <x-spire::button type="submit" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Criar Peça
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
