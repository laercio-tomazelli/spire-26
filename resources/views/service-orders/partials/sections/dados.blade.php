{{-- Seção: Dados da OS --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Informações Gerais --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Gerais</h3>
        </x-slot:header>

        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número da OS</dt>
                <dd class="mt-1 text-sm font-mono font-semibold text-gray-900 dark:text-white">
                    #{{ str_pad((string) $serviceOrder->order_number, 6, '0', STR_PAD_LEFT) }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Protocolo</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->protocol ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->status)
                        <x-spire::badge :variant="$serviceOrder->status->color ?? 'secondary'">
                            {{ $serviceOrder->status->name }}
                        </x-spire::badge>
                    @else
                        <span class="text-sm text-gray-500">—</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sub-Status</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->subStatus)
                        <x-spire::badge variant="secondary">
                            {{ $serviceOrder->subStatus->name }}
                        </x-spire::badge>
                    @else
                        <span class="text-sm text-gray-500">—</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prioridade</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->priority)
                        <x-spire::badge :variant="$serviceOrder->priority->color ?? 'info'">
                            {{ $serviceOrder->priority->name }}
                        </x-spire::badge>
                    @else
                        <span class="text-sm text-gray-500">Normal</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Origem</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->origin?->name ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Serviço</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->serviceType?->name ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Garantia</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->warranty_type === 'in_warranty')
                        <x-spire::badge variant="success">Em Garantia</x-spire::badge>
                    @elseif ($serviceOrder->warranty_type === 'out_of_warranty')
                        <x-spire::badge variant="warning">Fora de Garantia</x-spire::badge>
                    @else
                        <span class="text-sm text-gray-500">—</span>
                    @endif
                </dd>
            </div>
        </dl>
    </x-spire::card>

    {{-- Referências Externas --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Referências Externas</h3>
        </x-slot:header>

        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pré-OS Fabricante</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->manufacturer_pre_order ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">OS Fabricante</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->manufacturer_order ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">OS Posto</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->partner_order ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Externo (TPV)</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->external_id ?? '—' }}
                </dd>
            </div>
        </dl>
    </x-spire::card>

    {{-- Datas do Fluxo --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Datas do Fluxo</h3>
        </x-slot:header>

        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Abertura</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->opened_at ? \Carbon\Carbon::parse($serviceOrder->opened_at)->format('d/m/Y H:i') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Recebimento</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->received_at?->format('d/m/Y H:i') ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Avaliação</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->evaluated_at ? \Carbon\Carbon::parse($serviceOrder->evaluated_at)->format('d/m/Y H:i') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Reparo</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->repaired_at ? \Carbon\Carbon::parse($serviceOrder->repaired_at)->format('d/m/Y H:i') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fechamento</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->closed_at?->format('d/m/Y H:i') ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Visita Agendada</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->scheduled_visit_date ? \Carbon\Carbon::parse($serviceOrder->scheduled_visit_date)->format('d/m/Y') : '—' }}
                </dd>
            </div>
        </dl>
    </x-spire::card>

    {{-- Posto Autorizado --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Posto Autorizado</h3>
        </x-slot:header>

        @if ($serviceOrder->partner)
            <div class="flex items-start gap-4">
                <x-spire::avatar size="lg" :name="$serviceOrder->partner->trade_name" />
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $serviceOrder->partner->trade_name }}
                    </p>
                    @if ($serviceOrder->partner->name !== $serviceOrder->partner->trade_name)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $serviceOrder->partner->name }}
                        </p>
                    @endif
                    @if ($serviceOrder->partner->city && $serviceOrder->partner->state)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            {{ $serviceOrder->partner->city }}/{{ $serviceOrder->partner->state }}
                        </p>
                    @endif
                    @if ($serviceOrder->partner->phone)
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $serviceOrder->partner->phone }}
                        </p>
                    @endif
                </div>
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">Nenhum posto autorizado atribuído.</p>
        @endif
    </x-spire::card>
</div>
