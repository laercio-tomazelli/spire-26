<x-layouts.module title="Editar Perfil - {{ $role->name }}">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Perfis', 'href' => route('roles.index')],
            ['label' => $role->name, 'href' => route('roles.show', $role)],
            ['label' => 'Editar'],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Altere os dados do perfil
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('roles.show', $role) }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    @if ($role->is_system)
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg dark:bg-amber-900/20 dark:border-amber-800">
            <div class="flex items-center gap-2 text-amber-700 dark:text-amber-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-medium">Este é um perfil de sistema e não pode ser editado.</span>
            </div>
        </div>
    @endif

    <form action="{{ route('roles.update', $role) }}" method="POST" id="role-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Dados Básicos --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados do Perfil</h2>

                    <div class="space-y-4">
                        <x-spire::input type="text" name="name" label="Nome" placeholder="Nome do perfil"
                            :value="old('name', $role->name)" :error="$errors->first('name')" required :disabled="$role->is_system" />

                        <x-spire::textarea name="description" label="Descrição"
                            placeholder="Descrição das responsabilidades deste perfil" :value="old('description', $role->description)"
                            :error="$errors->first('description')" rows="3" :disabled="$role->is_system" />
                    </div>
                </x-spire::card>

                {{-- Permissões --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Permissões</h2>

                    <div class="space-y-6">
                        @forelse ($permissionsByGroup as $group => $groupPermissions)
                            @php
                                $groupPermissionIds = $groupPermissions->pluck('id')->toArray();
                                $checkedInGroup = count(array_intersect($groupPermissionIds, $rolePermissionIds));
                                $allChecked = $checkedInGroup === count($groupPermissionIds);
                                $someChecked = $checkedInGroup > 0 && !$allChecked;
                            @endphp
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <div
                                    class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox"
                                            class="group-toggle rounded border-gray-300 text-blue-600"
                                            data-group="{{ $group ?? 'geral' }}" {{ $allChecked ? 'checked' : '' }}
                                            {{ $role->is_system ? 'disabled' : '' }}>
                                        <span class="font-medium text-gray-900 dark:text-white capitalize">
                                            {{ $group ?? 'Geral' }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            ({{ $checkedInGroup }}/{{ $groupPermissions->count() }} selecionadas)
                                        </span>
                                    </label>
                                </div>
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($groupPermissions as $permission)
                                        <label class="flex items-start gap-2 cursor-pointer">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="permission-checkbox mt-0.5 rounded border-gray-300 text-blue-600"
                                                data-group="{{ $group ?? 'geral' }}"
                                                {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}
                                                {{ $role->is_system ? 'disabled' : '' }}>
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
                        @if (!$role->is_system)
                            <x-spire::button type="submit" class="w-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Salvar Alterações
                            </x-spire::button>
                        @endif

                        <x-spire::button href="{{ route('roles.show', $role) }}" variant="ghost" class="w-full">
                            {{ $role->is_system ? 'Voltar' : 'Cancelar' }}
                        </x-spire::button>
                    </div>
                </x-spire::card>

                {{-- Estatísticas --}}
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estatísticas</h2>

                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Usuários</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $role->users_count ?? $role->users()->count() }}
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Permissões</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ count($rolePermissionIds) }}
                            </dd>
                        </div>
                    </dl>
                </x-spire::card>

                {{-- Danger Zone --}}
                @if (!$role->is_system)
                    <x-spire::card class="border-red-200 dark:border-red-800">
                        <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Zona de Perigo</h2>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Ao excluir este perfil, os usuários vinculados perderão as permissões associadas.
                        </p>

                        <button type="button" onclick="document.getElementById('delete-form').submit()"
                            class="w-full px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors">
                            Excluir Perfil
                        </button>
                    </x-spire::card>
                @endif
            </div>
        </div>
    </form>

    {{-- Delete Form --}}
    @if (!$role->is_system)
        <form id="delete-form" action="{{ route('roles.destroy', $role) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

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
