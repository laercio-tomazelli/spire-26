<x-layouts.module title="Editar Time">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Times', 'href' => route('teams.index')],
            ['label' => $team->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        Editar time: {{ $team->name }}
    </x-slot:header>

    <form action="{{ route('teams.update', $team) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Time</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-spire::label for="name" required>Nome</x-spire::label>
                        <x-spire::input type="text" name="name" id="name" :value="old('name', $team->name)" required />
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-spire::label for="is_active">Status</x-spire::label>
                        <x-spire::select name="is_active" id="is_active" :options="[['value' => '1', 'label' => 'Ativo'], ['value' => '0', 'label' => 'Inativo']]" :value="old('is_active', $team->is_active ? '1' : '0')" />
                    </div>
                </div>

                <div>
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 text-sm">{{ old('description', $team->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-spire::card>

        {{-- Team Members --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Membros do Time</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Selecione os usuários que farão parte deste time
            </p>
            @php
                $teamUserIds = old('users', $team->users->pluck('id')->toArray());
                $teamLeaderIds = old('leaders', $team->users->where('pivot.is_leader', true)->pluck('id')->toArray());
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($users as $user)
                    <label
                        class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer">
                        <input type="checkbox" name="users[]" value="{{ $user->id }}"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            {{ in_array($user->id, $teamUserIds) ? 'checked' : '' }}>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                        <label class="flex items-center gap-1 text-xs text-gray-500">
                            <input type="checkbox" name="leaders[]" value="{{ $user->id }}"
                                class="rounded border-gray-300 text-amber-600 focus:ring-amber-500"
                                {{ in_array($user->id, $teamLeaderIds) ? 'checked' : '' }}>
                            Líder
                        </label>
                    </label>
                @endforeach
            </div>
        </x-spire::card>

        {{-- Roles --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Perfis do Time</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Todos os membros herdarão as permissões destes
                perfis</p>
            @php
                $teamRoleIds = old('roles', $team->roles->pluck('id')->toArray());
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($roles as $role)
                    <label
                        class="flex items-start gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                            class="mt-0.5 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                            {{ in_array($role->id, $teamRoleIds) ? 'checked' : '' }}>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $role->name }}</p>
                            @if ($role->description)
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $role->description }}</p>
                            @endif
                            <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                {{ $role->permissions_count ?? $role->permissions->count() }} permissões
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>
        </x-spire::card>

        {{-- Direct Permissions --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Permissões Diretas</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Permissões adicionais para todos os membros do time
            </p>
            @php
                $teamPermissionIds = old('permissions', $team->permissions->pluck('id')->toArray());
            @endphp
            @foreach ($permissions as $group => $groupPermissions)
                <div class="mb-6 last:mb-0">
                    <h4
                        class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 pb-2 border-b border-gray-200 dark:border-gray-700 capitalize">
                        {{ $group ?: 'Geral' }}
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach ($groupPermissions as $permission)
                            <label class="flex items-center gap-2 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                    {{ in_array($permission->id, $teamPermissionIds) ? 'checked' : '' }}>
                                <div>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $permission->name }}</span>
                                    <span
                                        class="text-xs text-gray-400 dark:text-gray-500 font-mono ml-1">{{ $permission->slug }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </x-spire::card>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4">
            <x-spire::button variant="ghost" href="{{ route('teams.index') }}">
                Cancelar
            </x-spire::button>
            <x-spire::button type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Salvar Alterações
            </x-spire::button>
        </div>
    </form>
</x-layouts.module>
