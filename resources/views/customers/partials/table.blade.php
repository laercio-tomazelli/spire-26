{{-- Partial para requisições AJAX da tabela de clientes --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="Cliente" sortable sortField="name" data-column="customer" />
            <x-ui.table.column label="CPF/CNPJ" sortable sortField="document" data-column="document" />
            <x-ui.table.column label="Contato" data-column="contact" />
            <x-ui.table.column label="Localização" data-column="location" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($customers as $customer)
                <x-ui.table.row :record="$customer" :selectable="true" :clickable="true">
                    {{-- Customer Info --}}
                    <x-ui.table.cell data-column="customer">
                        <div class="flex items-center gap-3">
                            <x-spire::avatar size="sm" :name="$customer->name" />
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $customer->name }}</p>
                                @if ($customer->trade_name)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->trade_name }}</p>
                                @endif
                                <x-spire::badge :variant="$customer->customer_type === 'PJ' ? 'info' : 'secondary'" size="sm">
                                    {{ $customer->customer_type === 'PJ' ? 'Pessoa Jurídica' : 'Pessoa Física' }}
                                </x-spire::badge>
                            </div>
                        </div>
                    </x-ui.table.cell>

                    {{-- Document --}}
                    <x-ui.table.cell data-column="document">
                        <span class="font-mono text-sm">{{ $customer->formatted_document }}</span>
                    </x-ui.table.cell>

                    {{-- Contact --}}
                    <x-ui.table.cell data-column="contact">
                        <div class="space-y-1">
                            @if ($customer->email)
                                <p class="text-sm">{{ $customer->email }}</p>
                            @endif
                            @if ($customer->phone || $customer->mobile)
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $customer->phone ?: $customer->mobile }}
                                </p>
                            @endif
                        </div>
                    </x-ui.table.cell>

                    {{-- Location --}}
                    <x-ui.table.cell data-column="location">
                        @if ($customer->city && $customer->state)
                            <p class="text-sm">{{ $customer->city }}/{{ $customer->state }}</p>
                            @if ($customer->neighborhood)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->neighborhood }}</p>
                            @endif
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        <x-ui.table.action :href="route('customers.show', $customer)" tooltip="Visualizar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        <x-ui.table.action :href="route('customers.edit', $customer)" tooltip="Editar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        <x-ui.table.action color="danger" tooltip="Excluir"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                            onclick="if(confirm('Tem certeza que deseja excluir este cliente?')) { document.getElementById('delete-customer-{{ $customer->id }}').submit(); }" />
                        <form id="delete-customer-{{ $customer->id }}"
                            action="{{ route('customers.destroy', $customer) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhum cliente encontrado"
                    description="Não há clientes cadastrados ou que correspondam aos filtros.">
                    <x-slot:action>
                        <x-spire::button href="{{ route('customers.create') }}">
                            Cadastrar primeiro cliente
                        </x-spire::button>
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination Footer --}}
<x-ui.table.pagination :paginator="$customers" />
