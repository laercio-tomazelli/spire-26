{{--
    Detalhes da Movimentação de Estoque
--}}

<x-layouts.module title="Detalhes da Movimentação">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Estoque'],
            ['label' => 'Movimentações', 'href' => route('inventory-transactions.index')],
            ['label' => '#' . $inventoryTransaction->id]
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-3">
            @php
                $isPositive = $inventoryTransaction->quantity > 0;
                $icon = $isPositive ? 'heroicon-o-arrow-down-tray' : 'heroicon-o-arrow-up-tray';
                $bgClass = $isPositive ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30';
                $iconClass = $isPositive ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
            @endphp
            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $bgClass }}">
                <x-dynamic-component :component="$icon" class="h-6 w-6 {{ $iconClass }}" />
            </div>
            <div>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Movimentação #{{ $inventoryTransaction->id }}
                </span>
            </div>
        </div>
    </x-slot:header>

    {{-- Content --}}
    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Quantity --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Quantidade</div>
                @php
                    $colorClass = $isPositive 
                        ? 'text-green-600 dark:text-green-400' 
                        : 'text-red-600 dark:text-red-400';
                @endphp
                <div class="text-2xl font-bold {{ $colorClass }}">
                    {{ $isPositive ? '+' : '' }}{{ number_format($inventoryTransaction->quantity, 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-1">
                    {{ $isPositive ? 'Entrada' : 'Saída' }} de estoque
                </div>
            </div>

            {{-- Date/Time --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Data/Hora</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $inventoryTransaction->created_at->format('d/m/Y') }}
                </div>
                <div class="text-xs text-gray-400 mt-1">
                    {{ $inventoryTransaction->created_at->format('H:i:s') }}
                </div>
            </div>

            {{-- Unit Price --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Preço Unitário</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    @if($inventoryTransaction->unit_price)
                        R$ {{ number_format($inventoryTransaction->unit_price, 2, ',', '.') }}
                    @else
                        -
                    @endif
                </div>
            </div>

            {{-- Cost Price --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Preço de Custo</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    @if($inventoryTransaction->cost_price)
                        R$ {{ number_format($inventoryTransaction->cost_price, 2, ',', '.') }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>

        {{-- Details Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Part Information --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-heroicon-o-cog-6-tooth class="w-5 h-5 text-gray-400" />
                    Peça
                </h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Código</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $inventoryTransaction->part_code }}
                        </dd>
                    </div>
                    @if($inventoryTransaction->part)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Descrição</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white text-right max-w-xs">
                                {{ $inventoryTransaction->part->description }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Ver peça</dt>
                            <dd>
                                <a href="{{ route('parts.show', $inventoryTransaction->part) }}"
                                    class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-200">
                                    <span>Abrir cadastro</span>
                                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                                </a>
                            </dd>
                        </div>
                    @else
                        <div class="py-2 text-sm text-gray-500 italic">
                            Peça não encontrada no cadastro
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Warehouse Information --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-heroicon-o-building-storefront class="w-5 h-5 text-gray-400" />
                    Depósito
                </h3>
                @if($inventoryTransaction->warehouse)
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Código</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $inventoryTransaction->warehouse->code }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Nome</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $inventoryTransaction->warehouse->name }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Tipo</dt>
                            <dd>
                                @php
                                    $typeColors = [
                                        'main' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'partner' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                        'buffer' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
                                        'defective' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    ];
                                    $typeLabels = [
                                        'main' => 'Principal',
                                        'partner' => 'Parceiro',
                                        'buffer' => 'Buffer',
                                        'defective' => 'Defeito',
                                    ];
                                    $type = $inventoryTransaction->warehouse->type ?? 'main';
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $typeColors[$type] ?? $typeColors['main'] }}">
                                    {{ $typeLabels[$type] ?? $type }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Ver depósito</dt>
                            <dd>
                                <a href="{{ route('warehouses.show', $inventoryTransaction->warehouse) }}"
                                    class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-200">
                                    <span>Abrir cadastro</span>
                                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                                </a>
                            </dd>
                        </div>
                    </dl>
                @else
                    <div class="py-2 text-sm text-gray-500 italic">
                        Depósito não encontrado
                    </div>
                @endif
            </div>
        </div>

        {{-- Document & User Information --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Document Information --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5 text-gray-400" />
                    Documento
                </h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tipo de Documento</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $inventoryTransaction->documentType?->name ?? '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Número</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $inventoryTransaction->document_number ?? '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tipo de Transação</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $inventoryTransaction->transactionType?->name ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- User Information --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-heroicon-o-user class="w-5 h-5 text-gray-400" />
                    Responsável
                </h3>
                @if($inventoryTransaction->user)
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-lg font-bold text-primary-700 dark:text-primary-300">
                            {{ strtoupper(substr($inventoryTransaction->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $inventoryTransaction->user->name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $inventoryTransaction->user->email }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                            <x-heroicon-o-cpu-chip class="w-6 h-6 text-gray-400" />
                        </div>
                        <div>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">
                                Sistema
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Movimentação automática
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Observations --}}
        @if($inventoryTransaction->observations)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-heroicon-o-chat-bubble-bottom-center-text class="w-5 h-5 text-gray-400" />
                    Observações
                </h3>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $inventoryTransaction->observations }}</p>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('inventory-transactions.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Voltar para lista
            </a>
        </div>
    </div>
</x-layouts.module>
