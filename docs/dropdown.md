# Dropdown Component

O componente Dropdown fornece menus suspensos customizáveis com posicionamento automático e interações avançadas.

## Uso Básico

```blade
<x-spire::dropdown>
    <x-spire::dropdown-trigger>
        <x-spire::button>Menu</x-spire::button>
    </x-spire::dropdown-trigger>

    <x-spire::dropdown-content>
        <x-spire::dropdown-item>Opção 1</x-spire::dropdown-item>
        <x-spire::dropdown-item>Opção 2</x-spire::dropdown-item>
        <x-spire::dropdown-separator />
        <x-spire::dropdown-item>Sair</x-spire::dropdown-item>
    </x-spire::dropdown-content>
</x-spire::dropdown>
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `placement` | string | `"bottom-start"` | Posicionamento: `top`, `bottom`, `left`, `right` com variações |
| `offset` | number | `4` | Distância do trigger em pixels |
| `disabled` | boolean | `false` | Dropdown desabilitado |
| `persistent` | boolean | `false` | Mantém aberto ao clicar fora |
| `trigger` | string | `"click"` | Evento de abertura: `click`, `hover` |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |

## Componentes

### Dropdown Trigger

```blade
<x-spire::dropdown-trigger>
    <!-- Seu elemento gatilho aqui -->
    <x-spire::button variant="outline">Ações</x-spire::button>
</x-spire::dropdown-trigger>
```

### Dropdown Content

```blade
<x-spire::dropdown-content>
    <!-- Itens do menu -->
</x-spire::dropdown-content>
```

### Dropdown Item

```blade
<x-spire::dropdown-item @click="action">Texto do Item</x-spire::dropdown-item>
```

### Dropdown Separator

```blade
<x-spire::dropdown-separator />
```

## Exemplos

### Menu Básico

```blade
<x-spire::dropdown>
    <x-spire::dropdown-trigger>
        <x-spire::button variant="outline">
            Ações
            <x-spire::icon name="chevron-down" class="ml-2" />
        </x-spire::button>
    </x-spire::dropdown-trigger>

    <x-spire::dropdown-content>
        <x-spire::dropdown-item @click="edit">Editar</x-spire::dropdown-item>
        <x-spire::dropdown-item @click="duplicate">Duplicar</x-spire::dropdown-item>
        <x-spire::dropdown-item @click="share">Compartilhar</x-spire::dropdown-item>
        <x-spire::dropdown-separator />
        <x-spire::dropdown-item variant="danger" @click="delete">Excluir</x-spire::dropdown-item>
    </x-spire::dropdown-content>
</x-spire::dropdown>
```

### Com Ícones

```blade
<x-spire::dropdown>
    <x-spire::dropdown-trigger>
        <x-spire::button variant="ghost" size="sm">⋮</x-spire::button>
    </x-spire::dropdown-trigger>

    <x-spire::dropdown-content>
        <x-spire::dropdown-item>
            <x-spire::icon name="eye" class="mr-2" />
            Visualizar
        </x-spire::dropdown-item>
        <x-spire::dropdown-item>
            <x-spire::icon name="pencil" class="mr-2" />
            Editar
        </x-spire::dropdown-item>
        <x-spire::dropdown-item>
            <x-spire::icon name="trash" class="mr-2" />
            Excluir
        </x-spire::dropdown-item>
    </x-spire::dropdown-content>
</x-spire::dropdown>
```

### Posicionamento

```blade
<!-- Topo -->
<x-spire::dropdown placement="top">
    <x-spire::dropdown-trigger>
        <x-spire::button>Menu Superior</x-spire::button>
    </x-spire::dropdown-trigger>
    <x-spire::dropdown-content>
        <x-spire::dropdown-item>Opção 1</x-spire::dropdown-item>
    </x-spire::dropdown-content>
</x-spire::dropdown>

<!-- Direita -->
<x-spire::dropdown placement="right">
    <x-spire::dropdown-trigger>
        <x-spire::button>Menu Direito</x-spire::button>
    </x-spire::dropdown-trigger>
    <x-spire::dropdown-content>
        <x-spire::dropdown-item>Opção 1</x-spire::dropdown-item>
    </x-spire::dropdown-content>
</x-spire::dropdown>
```

### Hover Trigger

```blade
<x-spire::dropdown trigger="hover">
    <x-spire::dropdown-trigger>
        <span class="cursor-pointer text-blue-600 hover:text-blue-800">
            Passe o mouse
        </span>
    </x-spire::dropdown-trigger>

    <x-spire::dropdown-content>
        <x-spire::dropdown-item>Item 1</x-spire::dropdown-item>
        <x-spire::dropdown-item>Item 2</x-spire::dropdown-item>
    </x-spire::dropdown-content>
</x-spire::dropdown>
```

### Menu de Usuário

```blade
<x-spire::dropdown placement="bottom-end">
    <x-spire::dropdown-trigger>
        <div class="flex items-center space-x-2 cursor-pointer">
            <img src="{{ auth()->user()->avatar }}" class="w-8 h-8 rounded-full" />
            <span>{{ auth()->user()->name }}</span>
            <x-spire::icon name="chevron-down" />
        </div>
    </x-spire::dropdown-trigger>

    <x-spire::dropdown-content>
        <x-spire::dropdown-item>
            <x-spire::icon name="user" class="mr-2" />
            Perfil
        </x-spire::dropdown-item>
        <x-spire::dropdown-item>
            <x-spire::icon name="cog" class="mr-2" />
            Configurações
        </x-spire::dropdown-item>
        <x-spire::dropdown-separator />
        <x-spire::dropdown-item>
            <x-spire::icon name="arrow-right-on-rectangle" class="mr-2" />
            Sair
        </x-spire::dropdown-item>
    </x-spire::dropdown-content>
</x-spire::dropdown>
```

## Integração com Alpine.js

### Controle Programático

```blade
<div x-data="dropdownController">
    <x-spire::dropdown x-ref="dropdown">
        <x-spire::dropdown-trigger>
            <x-spire::button @click="open = !open">
                Menu Programático
            </x-spire::button>
        </x-spire::dropdown-trigger>

        <x-spire::dropdown-content>
            <x-spire::dropdown-item @click="selectItem('opcao1')">
                Opção 1
            </x-spire::dropdown-item>
            <x-spire::dropdown-item @click="selectItem('opcao2')">
                Opção 2
            </x-spire::dropdown-item>
        </x-spire::dropdown-content>
    </x-spire::dropdown>

    <p x-show="selectedItem" class="mt-2">
        Selecionado: <span x-text="selectedItem"></span>
    </p>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dropdownController', () => ({
        selectedItem: '',

        selectItem(item) {
            this.selectedItem = item;
            // Fecha o dropdown
            this.$refs.dropdown.close();
        }
    }));
});
</script>
```

### Menu Dinâmico

```blade
<div x-data="dynamicMenu">
    <x-spire::dropdown>
        <x-spire::dropdown-trigger>
            <x-spire::button>
                Ações (<span x-text="selectedCount"></span>)
            </x-spire::button>
        </x-spire::dropdown-trigger>

        <x-spire::dropdown-content>
            <x-spire::dropdown-item
                x-show="selectedCount > 0"
                @click="bulkEdit"
            >
                Editar Selecionados
            </x-spire::dropdown-item>
            <x-spire::dropdown-item
                x-show="selectedCount > 0"
                @click="bulkDelete"
            >
                Excluir Selecionados
            </x-spire::dropdown-item>
            <x-spire::dropdown-item @click="export">
                Exportar
            </x-spire::dropdown-item>
            <x-spire::dropdown-separator x-show="selectedCount > 0" />
            <x-spire::dropdown-item @click="refresh">
                Atualizar
            </x-spire::dropdown-item>
        </x-spire::dropdown-content>
    </x-spire::dropdown>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dynamicMenu', () => ({
        selectedCount: 3, // Simulado

        bulkEdit() {
            console.log('Editar itens selecionados');
        },

        bulkDelete() {
            console.log('Excluir itens selecionados');
        },

        export() {
            console.log('Exportar dados');
        },

        refresh() {
            console.log('Atualizar lista');
        }
    }));
});
</script>
```

### Submenus

```blade
<div x-data="submenuController">
    <x-spire::dropdown>
        <x-spire::dropdown-trigger>
            <x-spire::button>Arquivo</x-spire::button>
        </x-spire::dropdown-trigger>

        <x-spire::dropdown-content>
            <x-spire::dropdown-item @click="newFile">Novo</x-spire::dropdown-item>
            <x-spire::dropdown-item @click="openFile">Abrir</x-spire::dropdown-item>
            <x-spire::dropdown-separator />

            <!-- Submenu -->
            <div x-data="{ open: false }" class="relative">
                <x-spire::dropdown-item
                    @mouseenter="open = true"
                    @mouseleave="open = false"
                    class="flex items-center justify-between"
                >
                    Recente
                    <x-spire::icon name="chevron-right" class="ml-2" />
                </x-spire::dropdown-item>

                <div
                    x-show="open"
                    x-transition
                    class="absolute left-full top-0 ml-1"
                    @mouseenter="open = true"
                    @mouseleave="open = false"
                >
                    <x-spire::dropdown-content>
                        <x-spire::dropdown-item @click="openRecent('file1')">
                            documento.txt
                        </x-spire::dropdown-item>
                        <x-spire::dropdown-item @click="openRecent('file2')">
                            relatório.pdf
                        </x-spire::dropdown-item>
                    </x-spire::dropdown-content>
                </div>
            </div>
        </x-spire::dropdown-content>
    </x-spire::dropdown>
</div>
```

## Propriedades dos Itens

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `disabled` | boolean | `false` | Item desabilitado |
| `variant` | string | `"default"` | Variante: `default`, `danger` |
| `href` | string | - | Link para navegação |

## Acessibilidade

- **Navegação**: Tab order correto, arrow keys
- **Leitores de Tela**: Anúncios adequados
- **Estados**: `aria-expanded`, `aria-haspopup`
- **Foco**: Indicadores visuais claros
- **Escape**: Fecha com tecla Escape

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/dropdown-custom.css */
.spire-dropdown {
    @apply relative;
}

.spire-dropdown__content {
    @apply absolute z-50 mt-2 bg-white border border-gray-200 rounded-md shadow-lg;
}

.spire-dropdown__item {
    @apply px-4 py-2 text-sm cursor-pointer hover:bg-gray-100;
}

.spire-dropdown__item--danger {
    @apply text-red-600 hover:bg-red-50;
}

.spire-dropdown__separator {
    @apply border-t border-gray-200 my-1;
}
```

### Dropdown Customizado

```blade
<x-spire::dropdown class="custom-dropdown">
    <x-spire::dropdown-trigger>
        <x-spire::button class="custom-trigger">Menu</x-spire::button>
    </x-spire::dropdown-trigger>

    <x-spire::dropdown-content class="custom-content">
        <x-spire::dropdown-item class="custom-item">Item 1</x-spire::dropdown-item>
    </x-spire::dropdown-content>
</x-spire::dropdown>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `open` | Dropdown aberto | - |
| `close` | Dropdown fechado | - |
| `item-click` | Item clicado | `{ item: Element }` |

## Performance

- **Lazy Rendering**: Só renderiza quando necessário
- **Event Delegation**: Eventos otimizados
- **Memory Management**: Limpeza adequada
- **Positioning**: Cálculo eficiente de posição

## Testes

**Cobertura**: 12 testes automatizados
- Renderização e posicionamento
- Interações do usuário
- Estados e propriedades
- Acessibilidade
- Performance

## Relacionados

- [Button](button.md) - Para triggers
- [Modal](modal.md) - Para conteúdo maior
- [Tooltip](tooltip.md) - Para dicas simples
- [Context Menu](context-menu.md) - Para menus de contexto