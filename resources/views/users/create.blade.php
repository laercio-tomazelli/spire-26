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

    <form action="{{ route('users.store') }}" method="POST" id="user-form">
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
                <x-spire::card id="vinculo-card" class="hidden">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vínculo</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Partner Select --}}
                        <div id="partner-field" class="hidden">
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
                            <div class="mt-4">
                                <x-spire::checkbox name="is_partner_admin" label="Administrador do Posto"
                                    :checked="old('is_partner_admin', false)" />
                            </div>
                        </div>

                        {{-- Manufacturer Select --}}
                        <div id="manufacturer-field" class="hidden">
                            <x-spire::select name="manufacturer_id" label="Fabricante"
                                placeholder="Selecione o fabricante" :value="old('manufacturer_id')" :options="$manufacturers
                                    ->map(fn($m) => ['value' => (string) $m->id, 'label' => $m->name])
                                    ->toArray()" />
                            @error('manufacturer_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tenant Select --}}
                        <div id="tenant-field" class="hidden">
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
                            :value="old('user_type')" :options="$userTypes" />
                        @error('user_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <x-spire::checkbox name="is_active" label="Usuário ativo" :checked="old('is_active', true)" />
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
            document.addEventListener('DOMContentLoaded', () => {
                const vinculoCard = document.getElementById('vinculo-card');
                const partnerField = document.getElementById('partner-field');
                const manufacturerField = document.getElementById('manufacturer-field');
                const tenantField = document.getElementById('tenant-field');

                const updateVinculoVisibility = (userType) => {
                    // Hide all first
                    partnerField.classList.add('hidden');
                    manufacturerField.classList.add('hidden');
                    tenantField.classList.add('hidden');

                    // Show based on type
                    const showCard = ['partner', 'manufacturer', 'spire_client'].includes(userType);
                    vinculoCard.classList.toggle('hidden', !showCard);

                    if (userType === 'partner') {
                        partnerField.classList.remove('hidden');
                    } else if (userType === 'manufacturer') {
                        manufacturerField.classList.remove('hidden');
                    } else if (userType === 'spire_client') {
                        tenantField.classList.remove('hidden');
                    }
                };

                // Listen for select changes
                window.addEventListener('select-change', (e) => {
                    if (e.detail?.name === 'user_type') {
                        updateVinculoVisibility(e.detail.value);
                    }
                });

                // Initialize based on old value
                const initialUserType = '{{ old('user_type') }}';
                if (initialUserType) {
                    updateVinculoVisibility(initialUserType);
                }
            });
        </script>
    @endpush
</x-layouts.module>
