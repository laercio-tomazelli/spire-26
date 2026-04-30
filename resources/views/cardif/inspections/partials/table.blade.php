@php
    $search = trim((string) request('search', ''));
    $highlight = function (?string $value) use ($search): string {
        $value = (string) $value;
        if ($value === '' || $search === '' || mb_strlen($search) < 2) {
            return e($value);
        }
        $escaped = e($value);
        $pattern = '/(' . preg_quote($search, '/') . ')/iu';
        return (string) preg_replace(
            $pattern,
            '<mark class="bg-yellow-200 dark:bg-yellow-500/40 text-inherit rounded px-0.5">$1</mark>',
            $escaped,
        );
    };
@endphp

@if ($inspections->isEmpty())
    <x-spire::empty-state title="Nenhuma vistoria encontrada"
        description="Cadastre uma nova vistoria ou ajuste os filtros." icon="inbox" />
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead
                class="bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-2">Sinistro</th>
                    <th class="px-4 py-2">Cliente</th>
                    <th class="px-4 py-2">Produto</th>
                    <th class="px-4 py-2">Agendamento</th>
                    <th class="px-4 py-2">Analista</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($inspections as $i)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-4 py-2 font-mono text-xs">{!! $highlight($i->claim_number) !!}</td>
                        <td class="px-4 py-2">{!! $highlight($i->customer_name) !!}</td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $i->product ?? '—' }}</td>
                        <td class="px-4 py-2">
                            @if ($i->inspection_date)
                                {{ $i->inspection_date->format('d/m/Y') }}
                                @if ($i->inspection_time)
                                    <span
                                        class="text-gray-500">{{ \Illuminate\Support\Str::substr($i->inspection_time, 0, 5) }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $i->assignedTo?->name ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <x-spire::badge :variant="$i->status->badgeVariant()">
                                {{ $i->status->label() }}
                            </x-spire::badge>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <x-spire::icon-button href="{{ route('cardif.inspections.show', $i) }}" variant="ghost"
                                    color="success" size="sm" title="Visualizar">
                                    <x-spire::icon name="eye" class="w-4 h-4" />
                                </x-spire::icon-button>
                                <x-spire::icon-button href="{{ route('cardif.inspections.edit', $i) }}" variant="ghost"
                                    color="danger" size="sm" title="Editar">
                                    <x-spire::icon name="edit" class="w-4 h-4" />
                                </x-spire::icon-button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $inspections->onEachSide(1)->links() }}
    </div>
@endif
