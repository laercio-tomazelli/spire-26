{{-- Partial para requisições AJAX da tabela Filament-style --}}
{{-- Este partial retorna a tabela completa (thead + tbody) + paginação para substituir .fi-ta-content --}}
@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div class="overflow-x-auto">
    <table class="{{ $classes }}" data-striped="false" data-hoverable="true">
        {{-- Table Columns (thead) --}}
        <x-ui.table.columns :selectable="true">
            <x-ui.table.column label="Modelo" sortable sortField="model_name" data-column="product" />
            <x-ui.table.column label="Marca" sortable sortField="brand_id" data-column="brand" align="center" />
            <x-ui.table.column label="Categoria" data-column="category" align="center" />
            <x-ui.table.column label="Garantia" data-column="warranty" align="center" />
            <x-ui.table.column label="Status" data-column="status" align="center" />
            <th class="fi-ta-actions-header-cell"></th>
        </x-ui.table.columns>

        {{-- Table Body --}}
        <x-ui.table.body>
            @forelse ($productModels as $productModel)
                <x-ui.table.row :record="$productModel" :selectable="true" :clickable="true">
                    {{-- Product Info --}}
                    <x-ui.table.cell data-column="product">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-linear-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $productModel->model_name ?? $productModel->model_code }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $productModel->model_code }}
                                    @if ($productModel->ean)
                                        <span class="text-gray-400">|</span> {{ $productModel->ean }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </x-ui.table.cell>

                    {{-- Brand --}}
                    <x-ui.table.cell data-column="brand" align="center">
                        @if ($productModel->brand)
                            <x-spire::badge variant="info">
                                {{ $productModel->brand->name }}
                            </x-spire::badge>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Category --}}
                    <x-ui.table.cell data-column="category" align="center">
                        @if ($productModel->category)
                            <x-spire::badge variant="secondary">
                                {{ $productModel->category->name }}
                            </x-spire::badge>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Warranty --}}
                    <x-ui.table.cell data-column="warranty" align="center">
                        @if ($productModel->warranty_months)
                            <x-spire::badge variant="secondary">
                                {{ $productModel->warranty_months }} meses
                            </x-spire::badge>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </x-ui.table.cell>

                    {{-- Status --}}
                    <x-ui.table.cell data-column="status" align="center">
                        @php $status = \App\Enums\Status::fromBool($productModel->is_active) @endphp
                        <x-spire::badge :variant="$status->badgeVariant()" :icon="$status->icon()">
                            {{ $status->label() }}
                        </x-spire::badge>
                    </x-ui.table.cell>

                    {{-- Actions --}}
                    <x-ui.table.actions>
                        @can('view', $productModel)
                            <x-ui.table.action :href="route('product-models.show', $productModel)" tooltip="Visualizar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd"/></svg>' />
                        @endcan
                        @can('update', $productModel)
                            <x-ui.table.action :href="route('product-models.edit', $productModel)" tooltip="Editar"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/><path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/></svg>' />
                        @endcan
                        @can('delete', $productModel)
                            <x-ui.table.action color="danger" tooltip="Excluir"
                                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/></svg>'
                                onclick="if(confirm('Tem certeza que deseja excluir este modelo?')) { document.getElementById('delete-product-model-{{ $productModel->id }}').submit(); }" />
                            <form id="delete-product-model-{{ $productModel->id }}"
                                action="{{ route('product-models.destroy', $productModel) }}" method="POST"
                                class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcan
                    </x-ui.table.actions>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty-state title="Nenhum modelo de produto encontrado"
                    description="Não há modelos de produto cadastrados ou que correspondam aos filtros.">
                    <x-slot:action>
                        @can('create', App\Models\ProductModel::class)
                            <x-spire::button href="{{ route('product-models.create') }}">
                                Criar primeiro modelo
                            </x-spire::button>
                        @endcan
                    </x-slot:action>
                </x-ui.table.empty-state>
            @endforelse
        </x-ui.table.body>
    </table>
</div>

{{-- Pagination Footer --}}
@if ($productModels->hasPages())
    <x-ui.table.pagination :paginator="$productModels" />
@endif
