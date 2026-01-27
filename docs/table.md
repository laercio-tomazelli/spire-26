# Table Component

O componente Table fornece tabelas avançadas com funcionalidades de ordenação, busca, paginação e ações customizadas.

## Uso Básico

```blade
<x-spire::table :data="$users">
    <x-spire::table-column field="name" label="Nome" />
    <x-spire::table-column field="email" label="Email" />
    <x-spire::table-column field="status" label="Status" />
</x-spire::table>
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `data` | array | `[]` | Dados para exibir na tabela |
| `searchable` | boolean | `false` | Habilita busca global |
| `sortable` | boolean | `false` | Habilita ordenação |
| `paginated` | boolean | `false` | Habilita paginação |
| `perPage` | number | `10` | Itens por página |
| `loading` | boolean | `false` | Estado de carregamento |
| `emptyMessage` | string | `"Nenhum resultado encontrado"` | Mensagem quando vazia |

## Table Column

### Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `field` | string | - | Campo do objeto de dados |
| `label` | string | - | Cabeçalho da coluna |
| `sortable` | boolean | `false` | Coluna ordenável |
| `width` | string | `auto` | Largura da coluna |
| `align` | string | `"left"` | Alinhamento: `left`, `center`, `right` |

## Exemplos

### Tabela Simples

```blade
<x-spire::table :data="$products">
    <x-spire::table-column field="name" label="Produto" />
    <x-spire::table-column field="price" label="Preço" />
    <x-spire::table-column field="stock" label="Estoque" />
</x-spire::table>
```

### Tabela Ordenável

```blade
<x-spire::table :data="$users" sortable>
    <x-spire::table-column field="name" label="Nome" sortable />
    <x-spire::table-column field="email" label="Email" sortable />
    <x-spire::table-column field="created_at" label="Data" sortable />
</x-spire::table>
```

### Tabela com Busca

```blade
<x-spire::table :data="$items" searchable>
    <x-spire::table-column field="title" label="Título" />
    <x-spire::table-column field="category" label="Categoria" />
    <x-spire::table-column field="status" label="Status" />
</x-spire::table>
```

### Tabela Paginada

```blade
<x-spire::table :data="$orders" paginated :per-page="15">
    <x-spire::table-column field="id" label="ID" />
    <x-spire::table-column field="customer" label="Cliente" />
    <x-spire::table-column field="total" label="Total" />
    <x-spire::table-column field="status" label="Status" />
</x-spire::table>
```

### Tabela Completa

```blade
<x-spire::table
    :data="$users"
    searchable
    sortable
    paginated
    :per-page="20"
    empty-message="Nenhum usuário encontrado"
>
    <x-spire::table-column field="id" label="ID" width="80px" />
    <x-spire::table-column field="name" label="Nome" sortable />
    <x-spire::table-column field="email" label="Email" sortable />
    <x-spire::table-column field="role" label="Função" />
    <x-spire::table-column field="status" label="Status" />
    <x-spire::table-column label="Ações" align="center">
        <x-slot:cell="{ row }">
            <div class="flex space-x-2">
                <x-spire::button size="sm" variant="ghost" @click="editUser(row)">
                    Editar
                </x-spire::button>
                <x-spire::button size="sm" variant="danger" @click="deleteUser(row)">
                    Excluir
                </x-spire::button>
            </div>
        </x-slot:cell>
    </x-spire::table-column>
</x-spire::table>
```

## Colunas Customizadas

### Com Slots

```blade
<x-spire::table :data="$products">
    <x-spire::table-column field="name" label="Produto" />

    <x-spire::table-column field="price" label="Preço">
        <x-slot:cell="{ row }">
            <span class="font-semibold text-green-600">
                R$ {{ number_format(row.price, 2, ',', '.') }}
            </span>
        </x-slot:cell>
    </x-spire::table-column>

    <x-spire::table-column field="status" label="Status">
        <x-slot:cell="{ row }">
            <x-spire::badge :variant="row.status === 'active' ? 'success' : 'secondary'">
                {{ ucfirst(row.status) }}
            </x-spire::badge>
        </x-slot:cell>
    </x-spire::table-column>

    <x-spire::table-column label="Ações">
        <x-slot:cell="{ row }">
            <x-spire::dropdown>
                <x-spire::dropdown-trigger>
                    <x-spire::button variant="ghost" size="sm">⋮</x-spire::button>
                </x-spire::dropdown-trigger>
                <x-spire::dropdown-content>
                    <x-spire::dropdown-item @click="viewProduct(row)">Ver</x-spire::dropdown-item>
                    <x-spire::dropdown-item @click="editProduct(row)">Editar</x-spire::dropdown-item>
                    <x-spire::dropdown-item @click="deleteProduct(row)">Excluir</x-spire::dropdown-item>
                </x-spire::dropdown-content>
            </x-spire::dropdown>
        </x-slot:cell>
    </x-spire::table-column>
</x-spire::table>
```

### Com Formatação Condicional

```blade
<x-spire::table :data="$inventory">
    <x-spire::table-column field="item" label="Item" />

    <x-spire::table-column field="quantity" label="Quantidade">
        <x-slot:cell="{ row }">
            <span :class="{
                'text-red-600 font-semibold': row.quantity < 10,
                'text-yellow-600': row.quantity >= 10 && row.quantity < 50,
                'text-green-600': row.quantity >= 50
            }">
                {{ row.quantity }}
            </span>
        </x-slot:cell>
    </x-spire::table-column>

    <x-spire::table-column field="last_updated" label="Última Atualização">
        <x-slot:cell="{ row }">
            {{ \Carbon\Carbon::parse(row.last_updated)->format('d/m/Y H:i') }}
        </x-slot:cell>
    </x-spire::table-column>
</x-spire::table>
```

## Funcionalidades Avançadas

### Busca Global

```blade
<x-spire::table :data="$data" searchable>
    <!-- Colunas -->
</x-spire::table>
```

A busca funciona em todos os campos visíveis da tabela.

### Ordenação

```blade
<x-spire::table :data="$data" sortable>
    <x-spire::table-column field="name" label="Nome" sortable />
    <x-spire::table-column field="date" label="Data" sortable />
</x-spire::table>
```

### Paginação

```blade
<x-spire::table :data="$data" paginated :per-page="25">
    <!-- Colunas -->
</x-spire::table>
```

### Estado de Carregamento

```blade
<x-spire::table :data="$data" :loading="$loading">
    <!-- Colunas -->
</x-spire::table>
```

## Integração com Alpine.js

### Busca Reativa

```blade
<div x-data="tableData">
    <div class="mb-4">
        <x-spire::input
            placeholder="Buscar..."
            x-model="search"
            @input.debounce.300ms="filterData"
        />
    </div>

    <x-spire::table :data="filteredData" searchable>
        <x-spire::table-column field="name" label="Nome" />
        <x-spire::table-column field="email" label="Email" />
    </x-spire::table>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tableData', () => ({
        search: '',
        originalData: @json($users),
        filteredData: @json($users),

        filterData() {
            if (this.search === '') {
                this.filteredData = this.originalData;
            } else {
                this.filteredData = this.originalData.filter(item =>
                    Object.values(item).some(value =>
                        String(value).toLowerCase().includes(this.search.toLowerCase())
                    )
                );
            }
        }
    }));
});
</script>
```

### Ações em Massa

```blade
<div x-data="bulkActions">
    <x-spire::table :data="$items" @selection-change="selected = $event.detail">
        <x-spire::table-column type="checkbox" />
        <x-spire::table-column field="name" label="Nome" />
        <x-spire::table-column field="status" label="Status" />
    </x-spire::table>

    <div x-show="selected.length > 0" class="mt-4 p-4 bg-blue-50 rounded">
        <p>{{ selected.length }} itens selecionados</p>
        <div class="mt-2 space-x-2">
            <x-spire::button @click="bulkDelete()">Excluir Selecionados</x-spire::button>
            <x-spire::button variant="outline" @click="bulkExport()">Exportar</x-spire::button>
        </div>
    </div>
</div>
```

## Acessibilidade

- **Semântica**: Usa `<table>`, `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>`
- **Navegação**: Teclado completo (Tab, Enter, Space, Arrow keys)
- **Leitores de Tela**: Anúncios adequados para ordenação e paginação
- **Foco**: Indicadores visuais claros
- **ARIA**: Labels e estados apropriados

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/table-custom.css */
.spire-table {
    @apply border border-gray-200 rounded-lg overflow-hidden;
}

.spire-table th {
    @apply bg-gray-50 font-semibold text-gray-700 px-4 py-3 text-left;
}

.spire-table td {
    @apply px-4 py-3 border-b border-gray-100;
}

.spire-table tr:hover {
    @apply bg-gray-50;
}
```

### Tabela Customizada

```blade
<x-spire::table :data="$data" class="custom-table">
    <x-spire::table-column field="name" label="Nome" class="font-bold" />
    <x-spire::table-column field="value" label="Valor" class="text-right text-green-600" />
</x-spire::table>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `table-sort` | Coluna ordenada | `{ field: string, direction: 'asc'\|'desc' }` |
| `table-search` | Busca realizada | `{ query: string }` |
| `table-page` | Página alterada | `{ page: number, perPage: number }` |
| `table-selection-change` | Seleção alterada | `{ selected: any[] }` |

## Performance

- **Virtualização**: Para tabelas muito grandes (>1000 linhas)
- **Debounced Search**: Busca otimizada
- **Lazy Loading**: Carregamento sob demanda
- **Memoização**: Cálculos cacheados

## Testes

**Cobertura**: 25 testes automatizados
- Renderização básica
- Funcionalidades (busca, ordenação, paginação)
- Interações do usuário
- Acessibilidade
- Estados edge case

## Relacionados

- [Button](button.md) - Para ações na tabela
- [Modal](modal.md) - Para edições em modal
- [Dropdown](dropdown.md) - Para menus de ação
- [Skeleton](skeleton.md) - Para estados de loading