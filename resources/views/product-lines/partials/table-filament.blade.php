{{-- Partial para requisições AJAX --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        <x-ui.table.columns :selectable="false">
            <x-ui.table.column label="Nome" sortable sortField="name" data-column="name" />
            <x-ui.table.column label="Categorias" data-column="categories" align="center" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        <x-ui.table.body>
            @forelse ($productLines as $productLine)
                <x-ui.table.row :record="$productLine" :selectable="false" :clickable="true">
                    <x-ui.table.cell data-column="name">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-linear-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($productLine->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $productLine->name }}</p>
                                @if ($productLine->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($productLine->description, 50) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </x-ui.table.cell>

                    <x-ui.table.cell data-column="categories" align="center">
                        <x-spire::badge variant="secondary">
                            {{ $productLine->categories_count ?? 0 }} categorias
                        </x-spire::badge>
                    </x-ui.table.cell>

                    <x-ui.table.actions>
                        <x-ui.table.action :href="route('product-lines.show', $productLine)" tooltip="Visualizar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        <x-ui.table.action :href="route('product-lines.edit', $productLine)" tooltip="Editar"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        <x-ui.table.action color="danger" tooltip="Excluir"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                            onclick="if(confirm('Tem certeza que deseja excluir esta linha de produto?')) { document.getElementById('delete-product-line-{{ $productLine->id }}').submit(); }" />
                        <form id="delete-product-line-{{ $productLine->id }}"
                            action="{{ route('product-lines.destroy', $productLine) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhuma linha de produto encontrada"
                    description="Não há linhas de produto cadastradas.">
                    <x-slot:action>
                        <x-spire::button href="{{ route('product-lines.create') }}">
                            Criar primeira linha
                        </x-spire::button>
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

@if ($productLines->hasPages())
    <x-ui.table.pagination :paginator="$productLines" />
@endif
