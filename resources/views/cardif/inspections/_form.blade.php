@php
    use App\Enums\InspectionStatus;
    /** @var \App\Models\Inspection $inspection */
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if (($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- Identificação --}}
    <x-spire::card>
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Identificação</h3>
        <div class="grid gap-4 md:grid-cols-3">
            <x-spire::input name="claim_number" label="Sinistro *" :value="old('claim_number', $inspection->claim_number)" :error="$errors->first('claim_number')" required />
            <x-spire::input name="reference_date" type="date" label="Data de referência" :value="old('reference_date', $inspection->reference_date?->format('Y-m-d'))"
                :error="$errors->first('reference_date')" />
            <x-spire::input name="tat_days" type="number" label="TAT (dias)" :value="old('tat_days', $inspection->tat_days)" :error="$errors->first('tat_days')" />
        </div>
    </x-spire::card>

    {{-- Cliente / Produto --}}
    <x-spire::card>
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Cliente e Produto</h3>
        <div class="grid gap-4 md:grid-cols-2">
            <x-spire::input name="customer_name" label="Cliente *" :value="old('customer_name', $inspection->customer_name)" :error="$errors->first('customer_name')" required />
            <x-spire::input name="customer_contact" label="Contato" :value="old('customer_contact', $inspection->customer_contact)" :error="$errors->first('customer_contact')" />
            <x-spire::input name="product" label="Produto" :value="old('product', $inspection->product)" :error="$errors->first('product')" />
            <div class="flex items-center gap-2 pt-7">
                <input type="hidden" name="has_electrical_damage" value="0">
                <input type="checkbox" id="has_electrical_damage" name="has_electrical_damage" value="1"
                    @checked(old('has_electrical_damage', $inspection->has_electrical_damage)) class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="has_electrical_damage" class="text-sm text-gray-700 dark:text-gray-300">
                    Possui dano elétrico
                </label>
            </div>
        </div>
    </x-spire::card>

    {{-- Agendamento --}}
    <x-spire::card>
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Agendamento</h3>
        <div class="grid gap-4 md:grid-cols-3">
            <x-spire::input name="contact_date" type="date" label="Data do contato" :value="old('contact_date', $inspection->contact_date?->format('Y-m-d'))"
                :error="$errors->first('contact_date')" />
            <x-spire::input name="inspection_date" type="date" label="Data da vistoria" :value="old('inspection_date', $inspection->inspection_date?->format('Y-m-d'))"
                :error="$errors->first('inspection_date')" />
            <x-spire::input name="inspection_time" type="time" label="Hora" :value="old('inspection_time', $inspection->inspection_time)"
                :error="$errors->first('inspection_time')" />

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Analista responsável
                </label>
                <select name="assigned_to_user_id"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">— Não atribuído —</option>
                    @foreach ($analysts as $a)
                        <option value="{{ $a['value'] }}" @selected((int) old('assigned_to_user_id', $inspection->assigned_to_user_id) === (int) $a['value'])>
                            {{ $a['label'] }}
                        </option>
                    @endforeach
                </select>
                @error('assigned_to_user_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status *</label>
                <select name="status" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach ($statuses as $s)
                        <option value="{{ $s['value'] }}" @selected(old('status', $inspection->status?->value) === $s['value'])>
                            {{ $s['label'] }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-spire::input name="report_sent_at" type="date" label="Envio do laudo" :value="old('report_sent_at', $inspection->report_sent_at?->format('Y-m-d'))"
                :error="$errors->first('report_sent_at')" />
        </div>
    </x-spire::card>

    {{-- Observações --}}
    <x-spire::card>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Observações</label>
        <textarea name="remark" rows="4"
            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('remark', $inspection->remark) }}</textarea>
        @error('remark')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </x-spire::card>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('cardif.inspections.index') }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">
            Cancelar
        </a>
        <x-spire::button type="submit">{{ $submitLabel ?? 'Salvar' }}</x-spire::button>
    </div>
</form>
