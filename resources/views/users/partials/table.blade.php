@if ($users->isEmpty())
    <x-spire::empty-state title="Nenhum usuário encontrado"
        description="Não há usuários cadastrados ou que correspondam aos filtros."
        icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'>
        <x-spire::button href="{{ route('users.create') }}">
            Criar primeiro usuário
        </x-spire::button>
    </x-spire::empty-state>
@else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-gray-400 text-sm">Usuário
                    </th>
                    <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-gray-400 text-sm">Tipo
                    </th>
                    <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-gray-400 text-sm">Vínculo
                    </th>
                    <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-gray-400 text-sm">Status
                    </th>
                    <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-gray-400 text-sm">Último
                        acesso</th>
                    <th class="text-right py-3 px-4 font-medium text-gray-500 dark:text-gray-400 text-sm">Ações
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="py-3 px-4 align-middle">
                            <div class="flex items-center gap-3">
                                <x-spire::avatar size="sm" :name="$user->name" />
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 align-middle">
                            <div class="flex flex-wrap items-center gap-1">
                                <x-spire::badge :variant="$user->user_type->badgeVariant()" :icon="$user->user_type->icon()">
                                    {{ $user->user_type->label() }}
                                </x-spire::badge>
                                @if ($user->is_partner_admin)
                                    <x-spire::badge variant="danger"
                                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'>Admin</x-spire::badge>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 align-middle text-sm text-gray-600 dark:text-gray-300">
                            @if ($user->partner)
                                {{ $user->partner->trade_name }}
                            @elseif ($user->manufacturer)
                                {{ $user->manufacturer->name }}
                            @elseif ($user->tenant)
                                {{ $user->tenant->name }}
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 align-middle">
                            @php $status = \App\Enums\Status::fromBool($user->is_active) @endphp
                            <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                                {{ $status->label() }}
                            </x-spire::badge>
                        </td>
                        <td class="py-3 px-4 align-middle text-sm text-gray-600 dark:text-gray-300">
                            @if ($user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                            @else
                                <span class="text-gray-400">Nunca</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 align-middle">
                            <div class="flex items-center justify-end gap-1">
                                <x-spire::icon-button href="{{ route('users.show', $user) }}" variant="ghost"
                                    size="sm" title="Visualizar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </x-spire::icon-button>

                                @can('update', $user)
                                    <x-spire::icon-button href="{{ route('users.edit', $user) }}" variant="ghost"
                                        size="sm" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </x-spire::icon-button>
                                @endcan

                                @can('update', $user)
                                    <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <x-spire::icon-button type="submit" variant="ghost" size="sm"
                                            :title="$user->is_active ? 'Desativar' : 'Ativar'">
                                            @if ($user->is_active)
                                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </x-spire::icon-button>
                                    </form>
                                @endcan

                                @can('delete', $user)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" x-data
                                        @submit.prevent="if (confirm('Tem certeza que deseja excluir este usuário?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <x-spire::icon-button type="submit" variant="ghost" size="sm" title="Excluir">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </x-spire::icon-button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 border-t border-gray-100 dark:border-gray-800 pt-4">
        {{ $users->links() }}
    </div>
@endif
