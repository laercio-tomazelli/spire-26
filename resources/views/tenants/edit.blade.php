<x-layouts.module title="Editar Tenant">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Tenants', 'href' => route('tenants.index')],
            ['label' => $tenant->name, 'href' => route('tenants.show', $tenant)],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Edite os dados do tenant
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('tenants.show', $tenant) }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('tenants.update', $tenant) }}" method="POST" id="tenant-form">
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
                            <x-spire::input type="text" name="name" label="Nome" placeholder="Nome do tenant"
                                :value="old('name', $tenant->name)" :error="$errors->first('name')" required />
                        </div>

                        <x-spire::input type="text" name="document" label="CNPJ" placeholder="00.000.000/0000-00"
                            :value="old('document', $tenant->document)" :error="$errors->first('document')" />

                        <x-spire::input type="email" name="email" label="E-mail" placeholder="contato@empresa.com"
                            :value="old('email', $tenant->email)" :error="$errors->first('email')" />

                        <x-spire::input type="text" name="phone" label="Telefone" placeholder="(00) 0000-0000"
                            :value="old('phone', $tenant->phone)" :error="$errors->first('phone')" />

                        <x-spire::input type="url" name="logo_url" label="URL do Logo"
                            placeholder="https://exemplo.com/logo.png" :value="old('logo_url', $tenant->logo_url)" :error="$errors->first('logo_url')" />
                    </div>
                </x-spire::card>

                {{-- Endereço --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-spire::input type="text" name="address" label="Endereço"
                                placeholder="Rua, número, complemento" :value="old('address', $tenant->address)" :error="$errors->first('address')" />
                        </div>

                        <x-spire::input type="text" name="city" label="Cidade" placeholder="Cidade"
                            :value="old('city', $tenant->city)" :error="$errors->first('city')" />

                        <x-spire::input type="text" name="state" label="Estado" placeholder="UF" maxlength="2"
                            :value="old('state', $tenant->state)" :error="$errors->first('state')" />

                        <x-spire::input type="text" name="postal_code" label="CEP" placeholder="00000-000"
                            :value="old('postal_code', $tenant->postal_code)" :error="$errors->first('postal_code')" />

                        <x-spire::input type="text" name="country" label="País" placeholder="Brasil"
                            :value="old('country', $tenant->country)" :error="$errors->first('country')" />
                    </div>
                </x-spire::card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>

                    <x-spire::checkbox name="is_active" label="Tenant ativo" :checked="old('is_active', $tenant->is_active)" />
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

                        <x-spire::button href="{{ route('tenants.show', $tenant) }}" variant="ghost" class="w-full">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>

                {{-- Danger Zone --}}
                <x-spire::card class="border-red-200 dark:border-red-900">
                    <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Zona de Perigo</h2>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Ações irreversíveis que afetam este tenant.
                    </p>

                    <x-spire::button type="button" variant="danger" class="w-full"
                        onclick="if(confirm('Tem certeza que deseja excluir este tenant? Esta ação não pode ser desfeita.')) { document.getElementById('delete-form').submit(); }">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Excluir Tenant
                    </x-spire::button>
                </x-spire::card>
            </div>
        </div>
    </form>

    <form id="delete-form" action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts.module>
