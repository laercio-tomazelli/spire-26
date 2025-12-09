<x-layouts.module title="Editar Fabricante - {{ $manufacturer->name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Fabricantes', 'href' => route('manufacturers.index')],
            ['label' => $manufacturer->name, 'href' => route('manufacturers.show', $manufacturer)],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Altere os dados do fabricante
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('manufacturers.show', $manufacturer) }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('manufacturers.update', $manufacturer) }}" method="POST" id="manufacturer-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-spire::input type="text" name="name" label="Nome"
                                placeholder="Nome do fabricante" :value="old('name', $manufacturer->name)" :error="$errors->first('name')" required />
                        </div>

                        <x-spire::input type="text" name="document" label="CNPJ" placeholder="00.000.000/0000-00"
                            :value="old('document', $manufacturer->document)" :error="$errors->first('document')" />

                        <x-spire::input type="email" name="email" label="E-mail"
                            placeholder="contato@fabricante.com" :value="old('email', $manufacturer->email)" :error="$errors->first('email')" />

                        <x-spire::input type="text" name="phone" label="Telefone" placeholder="(00) 0000-0000"
                            :value="old('phone', $manufacturer->phone)" :error="$errors->first('phone')" />

                        <x-spire::input type="url" name="website" label="Website"
                            placeholder="https://www.fabricante.com" :value="old('website', $manufacturer->website)" :error="$errors->first('website')" />

                        <x-spire::input type="url" name="logo_url" label="URL do Logo"
                            placeholder="https://exemplo.com/logo.png" :value="old('logo_url', $manufacturer->logo_url)" :error="$errors->first('logo_url')" />
                    </div>
                </x-spire::card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Tenant --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vínculo</h2>

                    <div class="space-y-4">
                        <x-spire::select name="tenant_id" label="Tenant" placeholder="Selecione o tenant"
                            :value="old('tenant_id', (string) $manufacturer->tenant_id)" :options="$tenants
                                ->map(fn($t) => ['value' => (string) $t->id, 'label' => $t->name])
                                ->toArray()" required />
                        @error('tenant_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </x-spire::card>

                {{-- Status --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>

                    <div class="space-y-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                {{ old('is_active', $manufacturer->is_active) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                Fabricante ativo
                            </span>
                        </label>
                    </div>
                </x-spire::card>

                {{-- Actions --}}
                <x-spire::card>
                    <div class="flex flex-col gap-3">
                        <x-spire::button type="submit" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Salvar Alterações
                        </x-spire::button>

                        <x-spire::button href="{{ route('manufacturers.show', $manufacturer) }}" variant="ghost"
                            class="w-full">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>

                {{-- Danger Zone --}}
                <x-spire::card class="border-red-200 dark:border-red-800">
                    <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Zona de Perigo</h2>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Ao excluir este fabricante, todas as suas marcas também serão removidas.
                    </p>

                    <button type="button" onclick="document.getElementById('delete-form').submit()"
                        class="w-full px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors">
                        Excluir Fabricante
                    </button>
                </x-spire::card>
            </div>
        </div>
    </form>

    {{-- Delete Form --}}
    <form id="delete-form" action="{{ route('manufacturers.destroy', $manufacturer) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts.module>
