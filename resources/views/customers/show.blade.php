<x-layouts.module title="Detalhes do Cliente">
    {{-- Breadcrumbs --}}
    <x-slot:breadcrumbs>
        <x-spire::breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Clientes', 'href' => route('customers.index')],
            ['label' => $customer->name],
        ]" />
    </x-slot:breadcrumbs>

    {{-- Header --}}
    <x-slot:header>
        <div class="flex items-center gap-3">
            <x-spire::avatar size="lg" :name="$customer->name" />
            <div>
                <span class="text-gray-600 dark:text-gray-400">{{ $customer->name }}</span>
                <x-spire::badge :variant="$customer->customer_type === 'PJ' ? 'info' : 'secondary'" class="ml-2">
                    {{ $customer->customer_type === 'PJ' ? 'Pessoa Jurídica' : 'Pessoa Física' }}
                </x-spire::badge>
            </div>
        </div>
    </x-slot:header>

    {{-- Header Actions --}}
    <x-slot:headerActions>
        <x-spire::button href="{{ route('customers.edit', $customer) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Editar
        </x-spire::button>

        <x-spire::button href="{{ route('customers.index') }}" variant="ghost">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </x-spire::button>
    </x-slot:headerActions>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-spire::alert type="success" class="mb-6">
            {{ session('success') }}
        </x-spire::alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Dados Básicos --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Nome / Razão Social</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $customer->name }}</dd>
                    </div>

                    @if ($customer->trade_name)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Nome Fantasia</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $customer->trade_name }}</dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $customer->customer_type === 'PJ' ? 'CNPJ' : 'CPF' }}</dt>
                        <dd class="text-gray-900 dark:text-white font-mono">{{ $customer->formatted_document }}</dd>
                    </div>

                    @if ($customer->state_registration)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Inscrição Estadual</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $customer->state_registration }}</dd>
                        </div>
                    @endif

                    @if ($customer->birth_date)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Data de Nascimento</dt>
                            <dd class="text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($customer->birth_date)->format('d/m/Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            {{-- Contato --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contato</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if ($customer->email)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">E-mail</dt>
                            <dd class="text-gray-900 dark:text-white">
                                <a href="mailto:{{ $customer->email }}"
                                    class="text-primary-600 hover:underline">{{ $customer->email }}</a>
                            </dd>
                        </div>
                    @endif

                    @if ($customer->phone)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Telefone</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $customer->phone }}</dd>
                        </div>
                    @endif

                    @if ($customer->phone_secondary)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Telefone Secundário</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $customer->phone_secondary }}</dd>
                        </div>
                    @endif

                    @if ($customer->mobile)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Celular</dt>
                            <dd class="text-gray-900 dark:text-white">
                                <a href="https://wa.me/55{{ preg_replace('/\D/', '', $customer->mobile) }}"
                                    target="_blank" class="text-green-600 hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                    </svg>
                                    {{ $customer->mobile }}
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-spire::card>

            {{-- Endereço --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço</h2>

                @if ($customer->address || $customer->city)
                    <dl class="space-y-3">
                        @if ($customer->postal_code)
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">CEP</dt>
                                <dd class="text-gray-900 dark:text-white font-mono">
                                    {{ substr($customer->postal_code, 0, 5) }}-{{ substr($customer->postal_code, 5, 3) }}
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Endereço</dt>
                            <dd class="text-gray-900 dark:text-white">
                                {{ $customer->address }}{{ $customer->address_number ? ', ' . $customer->address_number : '' }}
                                {{ $customer->address_complement ? ' - ' . $customer->address_complement : '' }}
                            </dd>
                        </div>

                        @if ($customer->neighborhood)
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Bairro</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $customer->neighborhood }}</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Cidade/Estado</dt>
                            <dd class="text-gray-900 dark:text-white">
                                {{ $customer->city }}{{ $customer->state ? '/' . $customer->state : '' }}
                                {{ $customer->country ? ' - ' . $customer->country : '' }}
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Endereço não informado.</p>
                @endif
            </x-spire::card>

            {{-- Observações --}}
            @if ($customer->observations)
                <x-spire::card>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações</h2>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $customer->observations }}</p>
                </x-spire::card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Ações Rápidas --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações</h2>

                <div class="space-y-3">
                    <x-spire::button href="{{ route('customers.edit', $customer) }}" class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Cliente
                    </x-spire::button>

                    {{-- TODO: Link para criar OS para este cliente
                    <x-spire::button variant="secondary" class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Nova Ordem de Serviço
                    </x-spire::button>
                    --}}

                    <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                        onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                        @csrf
                        @method('DELETE')
                        <x-spire::button type="submit" variant="danger" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Excluir Cliente
                        </x-spire::button>
                    </form>
                </div>
            </x-spire::card>

            {{-- Estatísticas --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estatísticas</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Ordens de Serviço</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $customer->serviceOrders->count() }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>

            {{-- Informações do Sistema --}}
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">ID</dt>
                        <dd class="text-gray-900 dark:text-white font-mono">{{ $customer->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Cadastrado em</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $customer->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Última atualização</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $customer->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </x-spire::card>
        </div>
    </div>
</x-layouts.module>
