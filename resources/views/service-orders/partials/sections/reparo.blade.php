{{-- Seção: Reparo --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Defeito --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Defeito</h3>
        </x-slot:header>

        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Defeito Relatado</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                    {{ $serviceOrder->reported_defect ?? 'Nenhum defeito relatado.' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Defeito Confirmado</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                    {{ $serviceOrder->confirmed_defect ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sintoma</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->symptom ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Defeito Catalogado</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->defect)
                        <x-spire::badge variant="secondary">
                            {{ $serviceOrder->defect->code }} - {{ $serviceOrder->defect->name }}
                        </x-spire::badge>
                    @else
                        <span class="text-sm text-gray-500">—</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Defeito Encontrado</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->defectFound)
                        <x-spire::badge variant="warning">
                            {{ $serviceOrder->defectFound->code }} - {{ $serviceOrder->defectFound->name }}
                        </x-spire::badge>
                    @else
                        <span class="text-sm text-gray-500">—</span>
                    @endif
                </dd>
            </div>
        </dl>

        {{-- Flags --}}
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap gap-2">
            @if ($serviceOrder->is_no_defect)
                <x-spire::badge variant="success">Sem Defeito</x-spire::badge>
            @endif
            @if ($serviceOrder->is_reentry)
                <x-spire::badge variant="warning">Reingresso</x-spire::badge>
            @endif
            @if ($serviceOrder->is_display)
                <x-spire::badge variant="info">Display</x-spire::badge>
            @endif
        </div>
    </x-spire::card>

    {{-- Solução --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Solução</h3>
        </x-slot:header>

        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição do Reparo</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                    {{ $serviceOrder->repair_description ?? 'Nenhuma descrição de reparo.' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Solução Catalogada</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->solution)
                        <x-spire::badge variant="success">
                            {{ $serviceOrder->solution->code }} - {{ $serviceOrder->solution->name }}
                        </x-spire::badge>
                    @else
                        <span class="text-sm text-gray-500">—</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Técnico Responsável</dt>
                <dd class="mt-1">
                    @if ($serviceOrder->technician)
                        <div class="flex items-center gap-2">
                            <x-spire::avatar size="sm" :name="$serviceOrder->technician->name" />
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $serviceOrder->technician->name }}
                            </span>
                        </div>
                    @else
                        <span class="text-sm text-gray-500">Não atribuído</span>
                    @endif
                </dd>
            </div>
        </dl>
    </x-spire::card>

    {{-- Peças Utilizadas --}}
    <x-spire::card class="lg:col-span-2">
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Peças Utilizadas</h3>
                @if ($serviceOrder->has_parts_used)
                    <x-spire::badge variant="success">Com peças</x-spire::badge>
                @endif
            </div>
        </x-slot:header>

        @if ($serviceOrder->parts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="py-2 font-medium">Código</th>
                            <th class="py-2 font-medium">Descrição</th>
                            <th class="py-2 font-medium text-center">Qtd</th>
                            <th class="py-2 font-medium text-right">Valor Unit.</th>
                            <th class="py-2 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($serviceOrder->parts as $part)
                            <tr>
                                <td class="py-2 font-mono text-gray-900 dark:text-white">
                                    {{ $part->part_code ?? ($part->part?->code ?? '—') }}
                                </td>
                                <td class="py-2 text-gray-900 dark:text-white">
                                    {{ $part->part_description ?? ($part->part?->description ?? '—') }}
                                </td>
                                <td class="py-2 text-center text-gray-900 dark:text-white">
                                    {{ $part->quantity }}
                                </td>
                                <td class="py-2 text-right text-gray-900 dark:text-white">
                                    R$ {{ number_format((float) ($part->unit_price ?? 0), 2, ',', '.') }}
                                </td>
                                <td class="py-2 text-right font-semibold text-gray-900 dark:text-white">
                                    R$ {{ number_format((float) ($part->total_price ?? 0), 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-gray-200 dark:border-gray-700">
                        <tr>
                            <td colspan="4" class="py-2 text-right font-semibold text-gray-900 dark:text-white">
                                Total de Peças:
                            </td>
                            <td class="py-2 text-right font-bold text-gray-900 dark:text-white">
                                R$ {{ number_format((float) $serviceOrder->parts->sum('total_price'), 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <x-spire::empty-state title="Nenhuma peça utilizada" description="Esta OS não possui peças cadastradas."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' />
        @endif
    </x-spire::card>

    {{-- Observações --}}
    <x-spire::card class="lg:col-span-2">
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Observações</h3>
        </x-slot:header>

        <div class="prose prose-sm dark:prose-invert max-w-none">
            {!! nl2br(e($serviceOrder->observations ?? 'Nenhuma observação.')) !!}
        </div>
    </x-spire::card>
</div>
