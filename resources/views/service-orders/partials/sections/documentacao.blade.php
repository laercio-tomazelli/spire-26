{{-- Seção: Documentação Técnica --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Logística de Entrada --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Logística de Entrada</h3>
        </x-slot:header>

        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nota Fiscal de Entrada</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->entry_invoice_number ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data da NF de Entrada</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->entry_invoice_date ? \Carbon\Carbon::parse($serviceOrder->entry_invoice_date)->format('d/m/Y') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Código de Rastreio</dt>
                <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">
                    {{ $serviceOrder->entry_tracking_code ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Recebimento</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->received_at?->format('d/m/Y H:i') ?? '—' }}
                </dd>
            </div>
        </dl>
    </x-spire::card>

    {{-- Logística de Saída --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Logística de Saída</h3>
        </x-slot:header>

        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nota Fiscal de Saída</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->exit_invoice_number ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data da NF de Saída</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->exit_invoice_date ? \Carbon\Carbon::parse($serviceOrder->exit_invoice_date)->format('d/m/Y') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Código de Rastreio</dt>
                <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">
                    {{ $serviceOrder->exit_tracking_code ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Envio</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->exit_sent_at ? \Carbon\Carbon::parse($serviceOrder->exit_sent_at)->format('d/m/Y H:i') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Entrega</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->delivered_at ? \Carbon\Carbon::parse($serviceOrder->delivered_at)->format('d/m/Y H:i') : '—' }}
                </dd>
            </div>
        </dl>
    </x-spire::card>

    {{-- Coleta (para domicílio) --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Coleta</h3>
        </x-slot:header>

        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número da Coleta</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->collection_number ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data da Coleta</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->collection_date ? \Carbon\Carbon::parse($serviceOrder->collection_date)->format('d/m/Y') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nota Fiscal de Coleta</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->collection_invoice_number ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data da NF de Coleta</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->collection_invoice_date ? \Carbon\Carbon::parse($serviceOrder->collection_invoice_date)->format('d/m/Y') : '—' }}
                </dd>
            </div>
        </dl>
    </x-spire::card>

    {{-- Troca/Devolução --}}
    <x-spire::card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Troca/Devolução</h3>
                @if ($serviceOrder->is_exchange)
                    <x-spire::badge variant="warning">Em Processo de Troca</x-spire::badge>
                @endif
            </div>
        </x-slot:header>

        @if ($serviceOrder->is_exchange)
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Troca</dt>
                    <dd class="mt-1">
                        <x-spire::badge :variant="$serviceOrder->exchange_type === 'refund' ? 'danger' : 'info'">
                            {{ $serviceOrder->exchange_type === 'refund' ? 'Devolução' : 'Troca de Produto' }}
                        </x-spire::badge>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Motivo</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $serviceOrder->exchange_reason ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Negociado</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                        @if ($serviceOrder->exchange_negotiated_value > 0)
                            R$ {{ number_format((float) $serviceOrder->exchange_negotiated_value, 2, ',', '.') }}
                        @else
                            —
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data da Análise</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $serviceOrder->exchange_analysis_date ? \Carbon\Carbon::parse($serviceOrder->exchange_analysis_date)->format('d/m/Y') : '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data da Aprovação</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $serviceOrder->exchange_approval_date ? \Carbon\Carbon::parse($serviceOrder->exchange_approval_date)->format('d/m/Y') : '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Resultado</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $serviceOrder->exchange_result ?? 'Pendente' }}
                    </dd>
                </div>
            </dl>
        @else
            <x-spire::empty-state title="Não é uma troca/devolução"
                description="Esta OS não está em processo de troca ou devolução."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>' />
        @endif
    </x-spire::card>

    {{-- Observações do Processo --}}
    <x-spire::card class="lg:col-span-2">
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Observações do Processo</h3>
        </x-slot:header>

        <div class="prose prose-sm dark:prose-invert max-w-none">
            {!! nl2br(e($serviceOrder->process_observations ?? 'Nenhuma observação do processo.')) !!}
        </div>
    </x-spire::card>
</div>
