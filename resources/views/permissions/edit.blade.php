<x-layouts.module title="Editar Permissão - {{ $permission->name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Permissões', 'href' => route('permissions.index')],
            ['label' => $permission->name, 'href' => route('permissions.show', $permission)],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Altere os dados da permissão
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('permissions.show', $permission) }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('permissions.update', $permission) }}" method="POST" id="permission-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados da Permissão</h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-spire::input type="text" name="name" label="Nome"
                                placeholder="Visualizar usuários" :value="old('name', $permission->name)" :error="$errors->first('name')" required />

                            <x-spire::input type="text" name="group" label="Grupo"
                                placeholder="Ex: users, tenants, roles" :value="old('group', $permission->group)" :error="$errors->first('group')"
                                list="groups-list" />
                            <datalist id="groups-list">
                                @foreach ($groups as $group)
                                    <option value="{{ $group }}">
                                @endforeach
                            </datalist>
                        </div>

                        <x-spire::textarea name="description" label="Descrição"
                            placeholder="Descreva o que esta permissão permite fazer" :value="old('description', $permission->description)"
                            :error="$errors->first('description')" rows="3" />

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Slug (gerado automaticamente)
                            </label>
                            <p
                                class="font-mono text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-800 px-3 py-2 rounded-lg">
                                {{ $permission->slug }}
                            </p>
                        </div>
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
                            Salvar Alterações
                        </x-spire::button>

                        <x-spire::button href="{{ route('permissions.show', $permission) }}" variant="ghost"
                            class="w-full">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>

                {{-- Estatísticas --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Uso</h2>

                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Perfis</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $permission->roles_count ?? $permission->roles()->count() }}
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Usuários diretos</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $permission->users_count ?? $permission->users()->count() }}
                            </dd>
                        </div>
                    </dl>
                </x-spire::card>

                {{-- Danger Zone --}}
                <x-spire::card class="border-red-200 dark:border-red-800">
                    <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Zona de Perigo</h2>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Ao excluir esta permissão, ela será removida de todos os perfis e usuários.
                    </p>

                    <button type="button" onclick="document.getElementById('delete-form').submit()"
                        class="w-full px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors">
                        Excluir Permissão
                    </button>
                </x-spire::card>
            </div>
        </div>
    </form>

    {{-- Delete Form --}}
    <form id="delete-form" action="{{ route('permissions.destroy', $permission) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts.module>
