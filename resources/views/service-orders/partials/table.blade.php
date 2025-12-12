{{-- Partial para requisições AJAX da tabela de ordens de serviço --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="OS" sortable sortField="order_number" data-column="order" />
            <x-ui.table.column label="Cliente" data-column="customer" />
            <x-ui.table.column label="Produto" data-column="product" />
            <x-ui.table.column label="Status" sortable sortField="status_id" data-column="status" />
            <x-ui.table.column label="Posto" data-column="partner" />
            <x-ui.table.column label="Datas" sortable sortField="opened_at" data-column="dates" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($serviceOrders as $order)
                <x-ui.table.row :record="$order" :selectable="true" :clickable="true"
                    data-href="{{ route('service-orders.show', $order) }}">
                    {{-- OS Number --}}
                    <x-ui.table.cell data-column="order">
                        <div>
                            <p class="font-mono font-semibold text-blue-600 dark:text-blue-400">
                                #{{ str_pad((string) $order->order_number, 6, '0', STR_PAD_LEFT) }}
                            </p>
                            @if ($order->protocol)
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->protocol }}</p>
                            @endif
                            @if ($order->manufacturer_order)
                                <p class="text-xs text-gray-500 dark:text-gray-400">Fab:
                                    {{ $order->manufacturer_order }}</p>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Customer --}}
                    <x-ui.table.cell data-column="customer">
                        @if ($order->customer)
                            <div class="flex items-center gap-2">
                                <x-spire::avatar size="xs" :name="$order->customer->name" />
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white truncate max-w-[200px]">
                                        {{ $order->customer->name }}
                                    </p>
                                    @if ($order->customer->phone || $order->customer->mobile)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $order->customer->phone ?: $order->customer->mobile }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Product --}}
                    <x-ui.table.cell data-column="product">
                        <div>
                            @if ($order->brand)
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $order->brand->name }}
                                </p>
                            @endif
                            @if ($order->productModel)
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $order->productModel->name }}
                                </p>
                            @elseif ($order->model_received)
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $order->model_received }}
                                </p>
                            @endif
                            @if ($order->serial_number)
                                <p class="text-xs font-mono text-gray-500 dark:text-gray-500">
                                    S/N: {{ $order->serial_number }}
                                </p>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Status --}}
                    <x-ui.table.cell data-column="status">
                        <div class="flex flex-col gap-1">
                            @if ($order->status)
                                <x-spire::badge :variant="$order->status->color ?? 'secondary'" size="sm">
                                    {{ $order->status->name }}
                                </x-spire::badge>
                            @endif
                            @if ($order->priority)
                                <x-spire::badge :variant="$order->priority->color ?? 'info'" size="sm">
                                    {{ $order->priority->name }}
                                </x-spire::badge>
                            @endif
                            @if ($order->is_critical)
                                <x-spire::badge variant="danger" size="sm">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Crítica
                                </x-spire::badge>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Partner --}}
                    <x-ui.table.cell data-column="partner">
                        @if ($order->partner)
                            <p class="text-sm text-gray-900 dark:text-white truncate max-w-[150px]">
                                {{ $order->partner->trade_name }}
                            </p>
                            @if ($order->partner->city && $order->partner->state)
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $order->partner->city }}/{{ $order->partner->state }}
                                </p>
                            @endif
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Dates --}}
                    <x-ui.table.cell data-column="dates">
                        <div class="text-sm space-y-1">
                            @if ($order->opened_at)
                                <p class="text-gray-600 dark:text-gray-400">
                                    <span class="text-gray-400">Abertura:</span>
                                    {{ \Carbon\Carbon::parse($order->opened_at)->format('d/m/Y') }}
                                </p>
                            @endif
                            @if ($order->closed_at)
                                <p class="text-green-600 dark:text-green-400">
                                    <span class="text-gray-400">Fechamento:</span>
                                    {{ $order->closed_at->format('d/m/Y') }}
                                </p>
                            @endif
                            @if ($order->scheduled_visit_date)
                                <p class="text-blue-600 dark:text-blue-400">
                                    <span class="text-gray-400">Agendada:</span>
                                    {{ \Carbon\Carbon::parse($order->scheduled_visit_date)->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        <x-ui.table.action :href="route('service-orders.show', $order)" tooltip="Visualizar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        <x-ui.table.action :href="route('service-orders.edit', $order)" tooltip="Editar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        @if (!$order->isClosed())
                            <x-ui.table.action color="danger" tooltip="Excluir"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                                onclick="if(confirm('Tem certeza que deseja excluir esta OS?')) { document.getElementById('delete-os-{{ $order->id }}').submit(); }" />
                            <form id="delete-os-{{ $order->id }}"
                                action="{{ route('service-orders.destroy', $order) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8">
                        <x-spire::empty-state title="Nenhuma ordem de serviço encontrada"
                            description="Não há ordens de serviço cadastradas ou que correspondam aos filtros."
                            icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'>
                            <x-spire::button href="{{ route('service-orders.create') }}">
                                Criar primeira OS
                            </x-spire::button>
                        </x-spire::empty-state>
                    </td>
                </tr>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination --}}
@if ($serviceOrders->hasPages())
    <x-ui.table.pagination :paginator="$serviceOrders" />
@endif
