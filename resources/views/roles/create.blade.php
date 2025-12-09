<x-layouts.module title="Novo Perfil">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Perfis', 'href' => route('roles.index')],
            ['label' => 'Novo Perfil'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Preencha os dados para criar um novo perfil
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('roles.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    <form action="{{ route('roles.store') }}" method="POST" id="role-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados do Perfil</h2>

                    <div class="space-y-4">
                        <x-spire::input type="text" name="name" label="Nome" placeholder="Nome do perfil"
                            :value="old('name')" :error="$errors->first('name')" required />

                        <x-spire::textarea name="description" label="Descrição"
                            placeholder="Descrição das responsabilidades deste perfil" :value="old('description')"
                            :error="$errors->first('description')" rows="3" />
                    </div>
                </x-spire::card>

                {{-- Permissões --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Permissões</h2>

                    <div class="space-y-6">
                        @forelse ($permissionsByGroup as $group => $groupPermissions)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <div
                                    class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox"
                                            class="group-toggle rounded border-gray-300 text-blue-600"
                                            data-group="{{ $group ?? 'geral' }}">
                                        <span class="font-medium text-gray-900 dark:text-white capitalize">
                                            {{ $group ?? 'Geral' }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            ({{ $groupPermissions->count() }} permissões)
                                        </span>
                                    </label>
                                </div>
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($groupPermissions as $permission)
                                        <label class="flex items-start gap-2 cursor-pointer">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="permission-checkbox mt-0.5 rounded border-gray-300 text-blue-600"
                                                data-group="{{ $group ?? 'geral' }}"
                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <div>
                                                <span class="text-sm text-gray-900 dark:text-white">
                                                    {{ $permission->name }}
                                                </span>
                                                @if ($permission->description)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $permission->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <p class="mt-2">Nenhuma permissão cadastrada no sistema.</p>
                            </div>
                        @endforelse
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
                            Criar Perfil
                        </x-spire::button>

                        <x-spire::button href="{{ route('roles.index') }}" variant="ghost" class="w-full">
                            Cancelar
                        </x-spire::button>
                    </div>
                </x-spire::card>

                {{-- Info --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <p>
                            <strong>Perfis</strong> definem conjuntos de permissões que podem ser atribuídos a
                            usuários.
                        </p>
                        <p>
                            Selecione as permissões que os usuários com este perfil terão acesso.
                        </p>
                    </div>
                </x-spire::card>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle de grupo de permissões
                document.querySelectorAll('.group-toggle').forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        const group = this.dataset.group;
                        const checkboxes = document.querySelectorAll(
                            `.permission-checkbox[data-group="${group}"]`);
                        checkboxes.forEach(cb => cb.checked = this.checked);
                    });
                });

                // Atualizar toggle do grupo quando permissões individuais mudam
                document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const group = this.dataset.group;
                        const groupCheckboxes = document.querySelectorAll(
                            `.permission-checkbox[data-group="${group}"]`);
                        const groupToggle = document.querySelector(
                            `.group-toggle[data-group="${group}"]`);

                        const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(groupCheckboxes).some(cb => cb.checked);

                        groupToggle.checked = allChecked;
                        groupToggle.indeterminate = someChecked && !allChecked;
                    });
                });
            });
        </script>
    @endpush
</x-layouts.module>
