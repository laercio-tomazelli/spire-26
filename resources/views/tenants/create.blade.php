<x-layouts.module title="Novo Tenant">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Tenants', 'href' => route('tenants.index')],
            ['label' => 'Novo Tenant'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Preencha os dados para criar um novo tenant
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('tenants.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('tenants.store') }}" method="POST" id="tenant-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-spire::input type="text" name="name" label="Nome" placeholder="Nome do tenant"
                                :value="old('name')" :error="$errors->first('name')" required />
                        </div>

                        <x-spire::input type="text" name="document" label="CNPJ" placeholder="00.000.000/0000-00"
                            :value="old('document')" :error="$errors->first('document')" />

                        <x-spire::input type="email" name="email" label="E-mail" placeholder="contato@empresa.com"
                            :value="old('email')" :error="$errors->first('email')" />

                        <x-spire::input type="text" name="phone" label="Telefone" placeholder="(00) 0000-0000"
                            :value="old('phone')" :error="$errors->first('phone')" />

                        <x-spire::input type="url" name="logo_url" label="URL do Logo"
                            placeholder="https://exemplo.com/logo.png" :value="old('logo_url')" :error="$errors->first('logo_url')" />
                    </div>
                </x-spire::card>

                {{-- Endereço --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-spire::input type="text" name="address" label="Endereço"
                                placeholder="Rua, número, complemento" :value="old('address')" :error="$errors->first('address')" />
                        </div>

                        <x-spire::input type="text" name="city" label="Cidade" placeholder="Cidade"
                            :value="old('city')" :error="$errors->first('city')" />

                        <x-spire::input type="text" name="state" label="Estado" placeholder="UF" maxlength="2"
                            :value="old('state')" :error="$errors->first('state')" />

                        <x-spire::input type="text" name="postal_code" label="CEP" placeholder="00000-000"
                            :value="old('postal_code')" :error="$errors->first('postal_code')" />

                        <x-spire::input type="text" name="country" label="País" placeholder="Brasil"
                            :value="old('country', 'Brasil')" :error="$errors->first('country')" />
                    </div>
                </x-spire::card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>

                    <x-spire::checkbox name="is_active" label="Tenant ativo" :checked="old('is_active', true)" />
                </x-spire::card>

                {{-- Actions --}}
                <x-spire::card>
                    <div class="flex flex-col gap-3">
                        <x-spire::button type="submit" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Criar Tenant
                        </x-spire::button>

                        <x-spire::button href="{{ route('tenants.index') }}" variant="ghost" class="w-full">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>
            </div>
        </div>
    </form>
</x-layouts.module>
