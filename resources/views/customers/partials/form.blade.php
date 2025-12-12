{{-- Formulário de Cliente (create/edit) --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Form --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Dados Básicos --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Básicos</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Tipo de Cliente --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de
                        Cliente</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="customer_type" value="PF" class="form-radio text-primary-600"
                                {{ old('customer_type', $customer?->customer_type ?? 'PF') === 'PF' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Pessoa Física</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="customer_type" value="PJ"
                                class="form-radio text-primary-600"
                                {{ old('customer_type', $customer?->customer_type) === 'PJ' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Pessoa Jurídica</span>
                        </label>
                    </div>
                    @error('customer_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CPF/CNPJ --}}
                <x-spire::input type="text" name="document" label="CPF/CNPJ" placeholder="000.000.000-00"
                    :value="old('document', $customer?->formatted_document ?? '')" :error="$errors->first('document')" data-mask="document" required />

                {{-- Nome/Razão Social --}}
                <div class="md:col-span-2">
                    <x-spire::input type="text" name="name" label="Nome / Razão Social"
                        placeholder="Digite o nome completo ou razão social" :value="old('name', $customer?->name ?? '')" :error="$errors->first('name')"
                        required />
                </div>

                {{-- Nome Fantasia --}}
                <div class="md:col-span-2" id="trade-name-field">
                    <x-spire::input type="text" name="trade_name" label="Nome Fantasia"
                        placeholder="Digite o nome fantasia" :value="old('trade_name', $customer?->trade_name ?? '')" :error="$errors->first('trade_name')" />
                </div>

                {{-- Inscrição Estadual --}}
                <div id="state-registration-field">
                    <x-spire::input type="text" name="state_registration" label="Inscrição Estadual"
                        placeholder="000.000.000.000" :value="old('state_registration', $customer?->state_registration ?? '')" :error="$errors->first('state_registration')" />
                </div>

                {{-- Data de Nascimento (PF) --}}
                <div id="birth-date-field">
                    <x-spire::input type="date" name="birth_date" label="Data de Nascimento" :value="old('birth_date', $customer?->birth_date ?? '')"
                        :error="$errors->first('birth_date')" />
                </div>
            </div>
        </x-spire::card>

        {{-- Contato --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contato</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-spire::input type="email" name="email" label="E-mail" placeholder="cliente@exemplo.com"
                    :value="old('email', $customer?->email ?? '')" :error="$errors->first('email')" />

                <x-spire::input type="text" name="phone" label="Telefone" placeholder="(00) 0000-0000"
                    :value="old('phone', $customer?->phone ?? '')" :error="$errors->first('phone')" data-mask="phone" />

                <x-spire::input type="text" name="phone_secondary" label="Telefone Secundário"
                    placeholder="(00) 0000-0000" :value="old('phone_secondary', $customer?->phone_secondary ?? '')" :error="$errors->first('phone_secondary')" data-mask="phone" />

                <x-spire::input type="text" name="mobile" label="Celular" placeholder="(00) 00000-0000"
                    :value="old('mobile', $customer?->mobile ?? '')" :error="$errors->first('mobile')" data-mask="mobile" />
            </div>
        </x-spire::card>

        {{-- Endereço --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Endereço</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- CEP com busca automática --}}
                <div class="md:col-span-1">
                    <div class="relative">
                        <x-spire::input type="text" name="postal_code" id="postal_code" label="CEP"
                            placeholder="00000-000" :value="old('postal_code', $customer?->postal_code ?? '')" :error="$errors->first('postal_code')" data-mask="cep"
                            data-cep-search="true" />
                        <div id="cep-loading" class="hidden absolute right-3 top-9">
                            <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p id="cep-error" class="mt-1 text-sm text-red-500 hidden"></p>
                </div>

                {{-- Logradouro --}}
                <div class="md:col-span-3">
                    <x-spire::input type="text" name="address" id="address" label="Logradouro"
                        placeholder="Rua, Avenida, etc." :value="old('address', $customer?->address ?? '')" :error="$errors->first('address')" />
                </div>

                {{-- Número --}}
                <div class="md:col-span-1">
                    <x-spire::input type="text" name="address_number" id="address_number" label="Número"
                        placeholder="123" :value="old('address_number', $customer?->address_number ?? '')" :error="$errors->first('address_number')" />
                </div>

                {{-- Complemento --}}
                <div class="md:col-span-1">
                    <x-spire::input type="text" name="address_complement" id="address_complement"
                        label="Complemento" placeholder="Apto, Sala, etc." :value="old('address_complement', $customer?->address_complement ?? '')" :error="$errors->first('address_complement')" />
                </div>

                {{-- Bairro --}}
                <div class="md:col-span-2">
                    <x-spire::input type="text" name="neighborhood" id="neighborhood" label="Bairro"
                        placeholder="Bairro" :value="old('neighborhood', $customer?->neighborhood ?? '')" :error="$errors->first('neighborhood')" />
                </div>

                {{-- Cidade --}}
                <div class="md:col-span-2">
                    <x-spire::input type="text" name="city" id="city" label="Cidade"
                        placeholder="Cidade" :value="old('city', $customer?->city ?? '')" :error="$errors->first('city')" />
                </div>

                {{-- Código IBGE (hidden) --}}
                <input type="hidden" name="city_code" id="city_code"
                    value="{{ old('city_code', $customer?->city_code ?? '') }}">

                {{-- Estado --}}
                <div class="md:col-span-1">
                    <x-spire::select name="state" id="state" label="Estado" placeholder="UF"
                        :value="old('state', $customer?->state ?? '')" :options="collect($states)
                            ->map(fn($label, $value) => ['value' => $value, 'label' => $value])
                            ->values()
                            ->toArray()" />
                    @error('state')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- País --}}
                <div class="md:col-span-1">
                    <x-spire::input type="text" name="country" label="País" :value="old('country', $customer?->country ?? 'Brasil')"
                        :error="$errors->first('country')" />
                </div>
            </div>
        </x-spire::card>

        {{-- Observações --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações</h2>

            <x-spire::textarea name="observations" placeholder="Observações adicionais sobre o cliente..."
                rows="4" :value="old('observations', $customer?->observations ?? '')" :error="$errors->first('observations')" />
        </x-spire::card>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Actions --}}
        <x-spire::card>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações</h2>

            <div class="space-y-3">
                <x-spire::button type="submit" class="w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $customer ? 'Atualizar Cliente' : 'Cadastrar Cliente' }}
                </x-spire::button>

                <x-spire::button type="button" variant="secondary" class="w-full" onclick="window.history.back()">
                    Cancelar
                </x-spire::button>
            </div>
        </x-spire::card>

        {{-- Informações --}}
        @if ($customer)
            <x-spire::card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Cadastrado em</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $customer->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Última atualização</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $customer->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </x-spire::card>
        @endif
    </div>
</div>

{{-- Script para busca de CEP e máscaras --}}
@push('scripts')
    <script type="module">
        import {
            initCustomerForm
        } from '{{ Vite::asset('resources/js/spire/modules/CustomerForm.ts') }}';
        initCustomerForm();
    </script>
@endpush
