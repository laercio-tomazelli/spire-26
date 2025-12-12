{{-- Seção: Consumidor --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Dados do Consumidor --}}
    <x-spire::card class="lg:col-span-2">
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dados do Consumidor</h3>
                @if ($serviceOrder->customer)
                    <a href="{{ route('customers.show', $serviceOrder->customer) }}"
                        class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                        Ver cadastro completo →
                    </a>
                @endif
            </div>
        </x-slot:header>

        @if ($serviceOrder->customer)
            <div class="flex items-start gap-4 mb-6">
                <x-spire::avatar size="xl" :name="$serviceOrder->customer->name" />
                <div>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $serviceOrder->customer->name }}
                    </p>
                    @if ($serviceOrder->customer->trade_name)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $serviceOrder->customer->trade_name }}
                        </p>
                    @endif
                    <x-spire::badge :variant="$serviceOrder->customer->customer_type === 'PJ' ? 'info' : 'secondary'" size="sm" class="mt-2">
                        {{ $serviceOrder->customer->customer_type === 'PJ' ? 'Pessoa Jurídica' : 'Pessoa Física' }}
                    </x-spire::badge>
                </div>
            </div>

            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $serviceOrder->customer->customer_type === 'PJ' ? 'CNPJ' : 'CPF' }}
                    </dt>
                    <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">
                        {{ $serviceOrder->customer->formatted_document ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $serviceOrder->customer->email ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $serviceOrder->customer->phone ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Celular</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $serviceOrder->customer->mobile ?? '—' }}
                    </dd>
                </div>
            </dl>
        @else
            <x-spire::empty-state title="Consumidor não vinculado"
                description="Esta OS não possui um consumidor vinculado."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' />
        @endif
    </x-spire::card>

    {{-- Endereço --}}
    <x-spire::card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Endereço</h3>
        </x-slot:header>

        @if ($serviceOrder->customer)
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CEP</dt>
                    <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">
                        {{ $serviceOrder->customer->postal_code ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Endereço</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if ($serviceOrder->customer->address)
                            {{ $serviceOrder->customer->address }}
                            @if ($serviceOrder->customer->number)
                                , {{ $serviceOrder->customer->number }}
                            @endif
                            @if ($serviceOrder->customer->complement)
                                - {{ $serviceOrder->customer->complement }}
                            @endif
                        @else
                            —
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bairro</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $serviceOrder->customer->neighborhood ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cidade/UF</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if ($serviceOrder->customer->city)
                            {{ $serviceOrder->customer->city }}/{{ $serviceOrder->customer->state }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>
        @else
            <p class="text-gray-500 dark:text-gray-400">—</p>
        @endif
    </x-spire::card>
</div>
