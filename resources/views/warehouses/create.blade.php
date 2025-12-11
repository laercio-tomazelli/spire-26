<x-layouts.module title="Novo Depósito">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Estoque'],
            ['label' => 'Depósitos', 'href' => route('warehouses.index')],
            ['label' => 'Novo'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Cadastre um novo depósito de estoque
    </x-slot:header>

    <x-spire::card>
        <form action="{{ route('warehouses.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Código --}}
                <x-spire::input name="code" label="Código" :value="old('code')" required maxlength="20"
                    placeholder="Ex: DEP001" :error="$errors->first('code')" />

                {{-- Nome --}}
                <x-spire::input name="name" label="Nome" :value="old('name')" required maxlength="100"
                    placeholder="Nome do depósito" :error="$errors->first('name')" />

                {{-- Tipo --}}
                <x-spire::select name="type" label="Tipo" :value="old('type', 'main')" required :options="[
                    ['value' => 'main', 'label' => 'Principal'],
                    ['value' => 'partner', 'label' => 'Parceiro'],
                    ['value' => 'buffer', 'label' => 'Buffer'],
                    ['value' => 'defective', 'label' => 'Defeituosos'],
                ]"
                    :error="$errors->first('type')" />

                {{-- Parceiro --}}
                <x-spire::select name="partner_id" label="Parceiro (opcional)" :value="old('partner_id')"
                    placeholder="Selecione um parceiro" :options="$partners->map(fn($p) => ['value' => $p->id, 'label' => $p->trade_name])->toArray()" :error="$errors->first('partner_id')" />

                {{-- Localização --}}
                <x-spire::input name="location" label="Localização" :value="old('location')" maxlength="255"
                    placeholder="Endereço ou localização física" :error="$errors->first('location')" />

                {{-- Descrição --}}
                <x-spire::input name="description" label="Descrição" :value="old('description')" maxlength="255"
                    placeholder="Descrição opcional" :error="$errors->first('description')" />

                {{-- Ativo --}}
                <div class="md:col-span-2">
                    <x-spire::toggle name="is_active" label="Depósito ativo" :checked="old('is_active', true)" />
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <x-spire::button variant="outline" href="{{ route('warehouses.index') }}">
                    Cancelar
                </x-spire::button>
                <x-spire::button type="submit">
                    Criar Depósito
                </x-spire::button>
            </div>
        </form>
    </x-spire::card>
</x-layouts.module>
