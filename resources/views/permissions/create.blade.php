<x-layouts.module title="Nova Permissão">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Permissões', 'href' => route('permissions.index')],
            ['label' => 'Nova Permissão'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Preencha os dados para criar uma nova permissão
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('permissions.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('permissions.store') }}" method="POST" id="permission-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados da Permissão</h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-spire::input type="text" name="name" label="Nome"
                                placeholder="Visualizar usuários" :value="old('name')" :error="$errors->first('name')" required />

                            <x-spire::input type="text" name="group" label="Grupo"
                                placeholder="Ex: users, tenants, roles" :value="old('group')" :error="$errors->first('group')"
                                list="groups-list" />
                            <datalist id="groups-list">
                                @foreach ($groups as $group)
                                    <option value="{{ $group }}">
                                @endforeach
                            </datalist>
                        </div>

                        <x-spire::textarea name="description" label="Descrição"
                            placeholder="Descreva o que esta permissão permite fazer" :value="old('description')"
                            :error="$errors->first('description')" rows="3" />
                    </div>
                </x-spire::card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Actions --}}
                <x-spire::card>
                    <div class="flex flex-col gap-3">
                        <x-spire::button type="submit" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Criar Permissão
                        </x-spire::button>

                        <x-spire::button href="{{ route('permissions.index') }}" variant="ghost" class="w-full">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>

                {{-- Info --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <p>
                            <strong>Permissões</strong> definem ações específicas que usuários podem realizar no
                            sistema.
                        </p>
                        <p>
                            Use <strong>grupos</strong> para organizar permissões relacionadas (ex: users, tenants).
                        </p>
                        <p>
                            O <strong>slug</strong> será gerado automaticamente a partir do grupo e nome.
                        </p>
                    </div>
                </x-spire::card>
            </div>
        </div>
    </form>
</x-layouts.module>
