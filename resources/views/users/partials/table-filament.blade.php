{{-- Partial para requisições AJAX da tabela Filament-style --}}
@forelse ($users as $user)
    <x-ui.table.row :record="$user" :selectable="true" :clickable="true">
        {{-- User Info --}}
        <x-ui.table.cell>
            <div class="flex items-center gap-3">
                <x-spire::avatar size="sm" :name="$user->name" />
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                </div>
            </div>
        </x-ui.table.cell>

        {{-- User Type --}}
        <x-ui.table.cell>
            <div class="flex flex-wrap items-center gap-1">
                <x-spire::badge :variant="$user->user_type->badgeVariant()" :icon="$user->user_type->icon()">
                    {{ $user->user_type->label() }}
                </x-spire::badge>
                @if ($user->is_partner_admin)
                    <x-spire::badge variant="danger">Admin</x-spire::badge>
                @endif
            </div>
        </x-ui.table.cell>

        {{-- Link/Vínculo --}}
        <x-ui.table.cell>
            @if ($user->partner)
                {{ $user->partner->trade_name }}
            @elseif ($user->manufacturer)
                {{ $user->manufacturer->name }}
            @elseif ($user->tenant)
                {{ $user->tenant->name }}
            @else
                <span class="text-gray-400">—</span>
            @endif
        </x-ui.table.cell>

        {{-- Status --}}
        <x-ui.table.cell>
            @php $status = \App\Enums\Status::fromBool($user->is_active) @endphp
            <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                {{ $status->label() }}
            </x-spire::badge>
        </x-ui.table.cell>

        {{-- Last Login --}}
        <x-ui.table.cell>
            @if ($user->last_login_at)
                {{ $user->last_login_at->diffForHumans() }}
            @else
                <span class="text-gray-400">Nunca</span>
            @endif
        </x-ui.table.cell>

        {{-- Actions --}}
        <x-ui.table.actions>
            <x-ui.table.action
                :href="route('users.edit', $user)"
                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>'
            >
                Editar
            </x-ui.table.action>
        </x-ui.table.actions>
    </x-ui.table.row>
@empty
    <x-ui.table.empty-state
        title="Nenhum usuário encontrado"
        description="Não há usuários cadastrados ou que correspondam aos filtros."
    >
        <x-slot:action>
            <x-spire::button href="{{ route('users.create') }}">
                Criar primeiro usuário
            </x-spire::button>
        </x-slot:action>
    </x-ui.table.empty-state>
@endforelse
