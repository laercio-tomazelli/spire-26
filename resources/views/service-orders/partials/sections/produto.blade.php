{{-- Seção: Produto --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Dados do Produto --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dados do Produto</h3>
        </x-slot:header>

        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Marca</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $serviceOrder->brand?->name ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Modelo Cadastrado</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->productModel?->name ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Modelo Recebido</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->model_received ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número de Série</dt>
                <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">
                    {{ $serviceOrder->serial_number ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Série Recebido</dt>
                <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">
                    {{ $serviceOrder->received_serial ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Categoria</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->productCategory?->name ?? '—' }}
                </dd>
            </div>
        </dl>
    </x-spire::card>

    {{-- Dados da Compra --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dados da Compra</h3>
        </x-slot:header>

        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Loja/Varejista</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->retailer_name ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nota Fiscal</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->purchase_invoice_number ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data da Compra</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->purchase_invoice_date ? \Carbon\Carbon::parse($serviceOrder->purchase_invoice_date)->format('d/m/Y') : '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor da Compra</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                    @if ($serviceOrder->purchase_value > 0)
                        R$ {{ number_format((float) $serviceOrder->purchase_value, 2, ',', '.') }}
                    @else
                        —
                    @endif
                </dd>
            </div>
        </dl>

        @if ($serviceOrder->purchase_invoice_file)
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ Storage::url($serviceOrder->purchase_invoice_file) }}" target="_blank"
                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    Ver Nota Fiscal
                </a>
            </div>
        @endif
    </x-spire::card>

    {{-- Acessórios e Condições --}}
    <x-spire::card class="lg:col-span-2">
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Acessórios e Condições</h3>
        </x-slot:header>

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Acessórios</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->accessories ?? 'Nenhum acessório informado.' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Condições do Produto</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $serviceOrder->conditions ?? 'Nenhuma condição informada.' }}
                </dd>
            </div>
        </dl>
    </x-spire::card>
</div>
