{{-- Seção: Valores da OS --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Resumo de Valores --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Resumo de Valores</h3>
        </x-slot:header>

        <dl class="space-y-4">
            <div class="flex justify-between">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Total de Peças</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                    R$
                    {{ number_format((float) ($serviceOrder->total_parts ?? ($serviceOrder->parts->sum('total_price') ?? 0)), 2, ',', '.') }}
                </dd>
            </div>

            <div class="flex justify-between">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Mão de Obra</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                    R$ {{ number_format((float) ($serviceOrder->labor_cost ?? 0), 2, ',', '.') }}
                </dd>
            </div>

            <div class="flex justify-between">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Deslocamento</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                    R$ {{ number_format((float) ($serviceOrder->km_cost ?? 0), 2, ',', '.') }}
                    @if ($serviceOrder->distance_km)
                        <span class="text-xs text-gray-500">({{ $serviceOrder->distance_km }} km)</span>
                    @endif
                </dd>
            </div>

            <div class="flex justify-between">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Custos Extras</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                    R$ {{ number_format((float) ($serviceOrder->extra_cost ?? 0), 2, ',', '.') }}
                </dd>
            </div>

            @if (($serviceOrder->total_discount ?? 0) > 0)
                <div class="flex justify-between text-red-600 dark:text-red-400">
                    <dt class="text-sm">Desconto</dt>
                    <dd class="text-sm font-medium">
                        - R$ {{ number_format((float) $serviceOrder->total_discount, 2, ',', '.') }}
                    </dd>
                </div>
            @endif

            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between">
                    <dt class="text-base font-semibold text-gray-900 dark:text-white">Total</dt>
                    <dd class="text-xl font-bold text-gray-900 dark:text-white">
                        R$ {{ number_format((float) ($serviceOrder->total ?? 0), 2, ',', '.') }}
                    </dd>
                </div>
            </div>
        </dl>
    </x-spire::card>

    {{-- Custos Detalhados --}}
    <x-spire::card class="lg:col-span-2">
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Custos Detalhados</h3>
        </x-slot:header>

        @if ($serviceOrder->costs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="py-2 font-medium">Tipo</th>
                            <th class="py-2 font-medium">Descrição</th>
                            <th class="py-2 font-medium text-right">Valor</th>
                            <th class="py-2 font-medium text-center">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($serviceOrder->costs as $cost)
                            <tr>
                                <td class="py-2">
                                    <x-spire::badge :variant="match ($cost->type ?? '') {
                                        'labor' => 'info',
                                        'travel' => 'warning',
                                        'parts' => 'success',
                                        default => 'secondary',
                                    }" size="sm">
                                        {{ match ($cost->type ?? '') {
                                            'labor' => 'Mão de Obra',
                                            'travel' => 'Deslocamento',
                                            'parts' => 'Peças',
                                            default => ucfirst($cost->type ?? 'Outro'),
                                        } }}
                                    </x-spire::badge>
                                </td>
                                <td class="py-2 text-gray-900 dark:text-white">
                                    {{ $cost->description ?? '—' }}
                                </td>
                                <td class="py-2 text-right font-medium text-gray-900 dark:text-white">
                                    R$ {{ number_format((float) ($cost->amount ?? 0), 2, ',', '.') }}
                                </td>
                                <td class="py-2 text-center text-gray-600 dark:text-gray-400">
                                    {{ $cost->created_at?->format('d/m/Y') ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-gray-200 dark:border-gray-700">
                        <tr>
                            <td colspan="2" class="py-2 text-right font-semibold text-gray-900 dark:text-white">
                                Total:
                            </td>
                            <td class="py-2 text-right font-bold text-gray-900 dark:text-white">
                                R$ {{ number_format((float) $serviceOrder->costs->sum('amount'), 2, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <x-spire::empty-state title="Nenhum custo cadastrado"
                description="Os custos da OS ainda não foram lançados."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
        @endif
    </x-spire::card>

    {{-- Faturamento --}}
    <x-spire::card class="lg:col-span-3">
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Faturamento</h3>
        </x-slot:header>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->is_invoiced)
                        <x-spire::badge variant="success">Faturado</x-spire::badge>
                    @else
                        <x-spire::badge variant="warning">Pendente</x-spire::badge>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número da NF</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->invoice_number ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Faturado</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                    @if ($serviceOrder->invoiced_amount)
                        R$ {{ number_format((float) $serviceOrder->invoiced_amount, 2, ',', '.') }}
                    @else
                        —
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data do Faturamento</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->invoiced_at?->format('d/m/Y') ?? '—' }}
                </dd>
            </div>
        </div>
    </x-spire::card>
</div>
