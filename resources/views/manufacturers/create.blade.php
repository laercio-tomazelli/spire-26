<x-layouts.module title="Novo Fabricante">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Fabricantes', 'href' => route('manufacturers.index')],
            ['label' => 'Novo Fabricante'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Preencha os dados para criar um novo fabricante
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('manufacturers.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('manufacturers.store') }}" method="POST" id="manufacturer-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-spire::input type="text" name="name" label="Nome"
                                placeholder="Nome do fabricante" :value="old('name')" :error="$errors->first('name')" required />
                        </div>

                        <x-spire::input type="text" name="document" label="CNPJ" placeholder="00.000.000/0000-00"
                            :value="old('document')" :error="$errors->first('document')" />

                        <x-spire::input type="email" name="email" label="E-mail"
                            placeholder="contato@fabricante.com" :value="old('email')" :error="$errors->first('email')" />

                        <x-spire::input type="text" name="phone" label="Telefone" placeholder="(00) 0000-0000"
                            :value="old('phone')" :error="$errors->first('phone')" />

                        <x-spire::input type="url" name="website" label="Website"
                            placeholder="https://www.fabricante.com" :value="old('website')" :error="$errors->first('website')" />

                        <x-spire::input type="url" name="logo_url" label="URL do Logo"
                            placeholder="https://exemplo.com/logo.png" :value="old('logo_url')" :error="$errors->first('logo_url')" />
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
                            :value="old('tenant_id')" :options="$tenants
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
                                {{ old('is_active', true) ? 'checked' : '' }}>
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
                            Criar Fabricante
                        </x-spire::button>

                        <x-spire::button href="{{ route('manufacturers.index') }}" variant="ghost" class="w-full">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>
            </div>
        </div>
    </form>
</x-layouts.module>
