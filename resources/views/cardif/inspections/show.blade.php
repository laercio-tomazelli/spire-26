<x-layouts.cardif>
    <x-slot:header>Vistoria #{{ $inspection->claim_number }}</x-slot:header>

    <div class="space-y-6 max-w-4xl">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Sinistro <span class="font-mono">#{{ $inspection->claim_number }}</span>
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $inspection->customer_name }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-spire::badge :variant="$inspection->status->badgeVariant()">
                    {{ $inspection->status->label() }}
                </x-spire::badge>
                <a href="{{ route('cardif.inspections.edit', $inspection) }}">
                    <x-spire::button>Editar</x-spire::button>
                </a>
            </div>
        </div>

        @if (session('success'))
            <x-spire::alert type="success">{{ session('success') }}</x-spire::alert>
        @endif

        <x-spire::card>
            <h3 class="text-base font-semibold mb-4">Dados do Sinistro</h3>
            <dl class="grid gap-4 md:grid-cols-3 text-sm">
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Data ref.</dt>
                    <dd>{{ $inspection->reference_date?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">TAT</dt>
                    <dd>{{ $inspection->tat_days ?? '—' }} dias</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Nº Laudos</dt>
                    <dd>{{ $inspection->reports_count }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Cliente</dt>
                    <dd>{{ $inspection->customer_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Contato</dt>
                    <dd>{{ $inspection->customer_contact ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Produto</dt>
                    <dd>{{ $inspection->product ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Dano elétrico</dt>
                    <dd>{{ $inspection->has_electrical_damage ? 'Sim' : 'Não' }}</dd>
                </div>
            </dl>
        </x-spire::card>

        <x-spire::card>
            <h3 class="text-base font-semibold mb-4">Agendamento</h3>
            <dl class="grid gap-4 md:grid-cols-3 text-sm">
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Data contato</dt>
                    <dd>{{ $inspection->contact_date?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Data vistoria</dt>
                    <dd>{{ $inspection->inspection_date?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Hora</dt>
                    <dd>{{ $inspection->inspection_time ? \Illuminate\Support\Str::substr($inspection->inspection_time, 0, 5) : '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Analista</dt>
                    <dd>{{ $inspection->assignedTo?->name ?? '— Não atribuído —' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Envio laudo</dt>
                    <dd>{{ $inspection->report_sent_at?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Criado por</dt>
                    <dd>{{ $inspection->createdBy?->name ?? 'Sistema' }}</dd>
                </div>
            </dl>
        </x-spire::card>

        @if ($inspection->remark)
            <x-spire::card>
                <h3 class="text-base font-semibold mb-2">Observações</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $inspection->remark }}</p>
            </x-spire::card>
        @endif

        <form method="POST" action="{{ route('cardif.inspections.destroy', $inspection) }}"
            onsubmit="return confirm('Excluir esta vistoria?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:underline">Excluir vistoria</button>
        </form>
    </div>
</x-layouts.cardif>
