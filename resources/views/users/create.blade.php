<x-layouts.module title="Novo Usuário">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Usuários', 'href' => route('users.index')],
            ['label' => 'Novo Usuário'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Preencha os dados para criar um novo usuário
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('users.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('users.store') }}" method="POST" x-data="userForm()"
        @select-change.window="handleUserTypeChange($event)">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-spire::input type="text" name="name" label="Nome Completo"
                                placeholder="Digite o nome completo" :value="old('name')" :error="$errors->first('name')" required />
                        </div>

                        <x-spire::input type="email" name="email" label="E-mail" placeholder="usuario@exemplo.com"
                            :value="old('email')" :error="$errors->first('email')" required />

                        <x-spire::input type="text" name="username" label="Usuário" placeholder="usuario123"
                            hint="Opcional. Para parceiros, pode ser o código do posto." :value="old('username')"
                            :error="$errors->first('username')" />

                        <x-spire::input type="text" name="phone" label="Telefone" placeholder="(00) 0000-0000"
                            :value="old('phone')" :error="$errors->first('phone')" />

                        <x-spire::input type="text" name="mobile" label="Celular" placeholder="(00) 00000-0000"
                            :value="old('mobile')" :error="$errors->first('mobile')" />
                    </div>
                </x-spire::card>

                {{-- Senha --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Senha</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-spire::input type="password" name="password" label="Senha" placeholder="••••••••"
                            :error="$errors->first('password')" password required />

                        <x-spire::input type="password" name="password_confirmation" label="Confirmar Senha"
                            placeholder="••••••••" password required />
                    </div>
                </x-spire::card>

                {{-- Vínculo (condicional) --}}
                <x-spire::card x-show="showPartnerField || showManufacturerField || showTenantField" x-cloak>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vínculo</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Partner Select --}}
                        <div x-show="showPartnerField">
                            <x-spire::select name="partner_id" label="Posto Autorizado" placeholder="Selecione o posto"
                                :value="old('partner_id')" :options="$partners
                                    ->map(
                                        fn($p) => [
                                            'value' => (string) $p->id,
                                            'label' => $p->code . ' - ' . $p->trade_name,
                                        ],
                                    )
                                    ->toArray()" />
                            @error('partner_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror

                            {{-- Is Partner Admin --}}
                            <label class="flex items-center gap-2 mt-4 cursor-pointer">
                                <input type="checkbox" name="is_partner_admin" value="1"
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ old('is_partner_admin') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    Administrador do Posto
                                </span>
                            </label>
                        </div>

                        {{-- Manufacturer Select --}}
                        <div x-show="showManufacturerField">
                            <x-spire::select name="manufacturer_id" label="Fabricante"
                                placeholder="Selecione o fabricante" :value="old('manufacturer_id')" :options="$manufacturers
                                    ->map(fn($m) => ['value' => (string) $m->id, 'label' => $m->name])
                                    ->toArray()" />
                            @error('manufacturer_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tenant Select --}}
                        <div x-show="showTenantField">
                            <x-spire::select name="tenant_id" label="Tenant" placeholder="Selecione o tenant"
                                :value="old('tenant_id')" :options="$tenants
                                    ->map(fn($t) => ['value' => (string) $t->id, 'label' => $t->name])
                                    ->toArray()" />
                            @error('tenant_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-spire::card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Tipo e Status --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tipo e Status</h2>

                    <div class="space-y-4">
                        <x-spire::select name="user_type" label="Tipo de Usuário" placeholder="Selecione o tipo"
                            :value="old('user_type')" :options="$userTypes" x-model="userType" />
                        @error('user_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                Usuário ativo
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
                            Criar Usuário
                        </x-spire::button>

                        <x-spire::button href="{{ route('users.index') }}" variant="ghost" class="w-full">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            function userForm() {
                return {
                    userType: '{{ old('user_type') }}',

                    handleUserTypeChange(event) {
                        if (event.detail.name === 'user_type') {
                            this.userType = event.detail.value;
                        }
                    },

                    get showPartnerField() {
                        return this.userType === 'partner';
                    },

                    get showManufacturerField() {
                        return this.userType === 'manufacturer';
                    },

                    get showTenantField() {
                        return this.userType === 'spire_client';
                    }
                }
            }
        </script>
    @endpush
</x-layouts.module>
