# Modal Component

O componente Modal fornece janelas modais acessíveis e flexíveis para exibir conteúdo sobreposto.

## Uso Básico

```blade
<x-spire::modal>
    <x-spire::modal-trigger>
        <x-spire::button>Abrir Modal</x-spire::button>
    </x-spire::modal-trigger>

    <x-spire::modal-content>
        <x-spire::modal-header>
            <x-spire::modal-title>Título do Modal</x-spire::modal-title>
        </x-spire::modal-header>

        <x-spire::modal-body>
            <p>Conteúdo do modal aqui.</p>
        </x-spire::modal-body>

        <x-spire::modal-footer>
            <x-spire::button variant="outline">Cancelar</x-spire::button>
            <x-spire::button>Confirmar</x-spire::button>
        </x-spire::modal-footer>
    </x-spire::modal-content>
</x-spire::modal>
```

## Estrutura dos Componentes

### Modal (Container Principal)
```blade
<x-spire::modal>
    <!-- Conteúdo do modal -->
</x-spire::modal>
```

### Modal Trigger (Gatilho)
```blade
<x-spire::modal-trigger>
    <x-spire::button>Abrir Modal</x-spire::button>
</x-spire::modal-trigger>
```

### Modal Content (Conteúdo)
```blade
<x-spire::modal-content>
    <!-- Conteúdo principal -->
</x-spire::modal-content>
```

### Modal Header
```blade
<x-spire::modal-header>
    <x-spire::modal-title>Título</x-spire::modal-title>
    <x-spire::modal-description>Descrição opcional</x-spire::modal-description>
</x-spire::modal-header>
```

### Modal Body
```blade
<x-spire::modal-body>
    <!-- Conteúdo principal do modal -->
</x-spire::modal-body>
```

### Modal Footer
```blade
<x-spire::modal-footer>
    <!-- Botões de ação -->
</x-spire::modal-footer>
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg`, `xl`, `full` |
| `persistent` | boolean | `false` | Impede fechamento ao clicar fora |
| `closeOnEscape` | boolean | `true` | Fecha com tecla Escape |
| `closeOnBackdrop` | boolean | `true` | Fecha ao clicar no fundo |

## Tamanhos

### Small
```blade
<x-spire::modal size="sm">
    <x-spire::modal-trigger>
        <x-spire::button>Modal Pequeno</x-spire::button>
    </x-spire::modal-trigger>
    <x-spire::modal-content>
        <p>Conteúdo compacto</p>
    </x-spire::modal-content>
</x-spire::modal>
```

### Medium (Padrão)
```blade
<x-spire::modal size="md">
    <!-- Conteúdo médio -->
</x-spire::modal>
```

### Large
```blade
<x-spire::modal size="lg">
    <!-- Conteúdo amplo -->
</x-spire::modal>
```

### Extra Large
```blade
<x-spire::modal size="xl">
    <!-- Conteúdo muito amplo -->
</x-spire::modal>
```

### Full Screen
```blade
<x-spire::modal size="full">
    <!-- Modal em tela cheia -->
</x-spire::modal>
```

## Exemplos Avançados

### Modal de Confirmação

```blade
<x-spire::modal>
    <x-spire::modal-trigger>
        <x-spire::button variant="danger">Excluir Item</x-spire::button>
    </x-spire::modal-trigger>

    <x-spire::modal-content>
        <x-spire::modal-header>
            <x-spire::modal-title>Confirmar Exclusão</x-spire::modal-title>
            <x-spire::modal-description>
                Esta ação não pode ser desfeita.
            </x-spire::modal-description>
        </x-spire::modal-header>

        <x-spire::modal-body>
            <p>Tem certeza que deseja excluir este item?</p>
        </x-spire::modal-body>

        <x-spire::modal-footer>
            <x-spire::button variant="outline" @click="$dispatch('modal-close')">
                Cancelar
            </x-spire::button>
            <x-spire::button variant="danger" @click="deleteItem()">
                Excluir
            </x-spire::button>
        </x-spire::modal-footer>
    </x-spire::modal-content>
</x-spire::modal>
```

### Modal com Formulário

```blade
<x-spire::modal>
    <x-spire::modal-trigger>
        <x-spire::button>Adicionar Usuário</x-spire::button>
    </x-spire::modal-trigger>

    <x-spire::modal-content>
        <x-spire::modal-header>
            <x-spire::modal-title>Novo Usuário</x-spire::modal-title>
        </x-spire::modal-header>

        <x-spire::modal-body>
            <form x-data="userForm" @submit.prevent="submitForm">
                <div class="space-y-4">
                    <x-spire::input
                        label="Nome"
                        name="name"
                        x-model="form.name"
                        required
                    />

                    <x-spire::input
                        label="Email"
                        type="email"
                        name="email"
                        x-model="form.email"
                        required
                    />

                    <x-spire::select
                        label="Função"
                        name="role"
                        :options="['Admin', 'User', 'Editor']"
                        x-model="form.role"
                    />
                </div>
            </form>
        </x-spire::modal-body>

        <x-spire::modal-footer>
            <x-spire::button variant="outline" @click="$dispatch('modal-close')">
                Cancelar
            </x-spire::button>
            <x-spire::button @click="submitForm()">
                Criar Usuário
            </x-spire::button>
        </x-spire::modal-footer>
    </x-spire::modal-content>
</x-spire::modal>
```

### Modal Persistente

```blade
<x-spire::modal persistent>
    <x-spire::modal-trigger>
        <x-spire::button>Modal Persistente</x-spire::button>
    </x-spire::modal-trigger>

    <x-spire::modal-content>
        <x-spire::modal-header>
            <x-spire::modal-title>Importante!</x-spire::modal-title>
        </x-spire::modal-header>

        <x-spire::modal-body>
            <p>Este modal só pode ser fechado pelos botões internos.</p>
        </x-spire::modal-body>

        <x-spire::modal-footer>
            <x-spire::button @click="$dispatch('modal-close')">
                Entendi
            </x-spire::button>
        </x-spire::modal-footer>
    </x-spire::modal-content>
</x-spire::modal>
```

### Modal com Scroll

```blade
<x-spire::modal>
    <x-spire::modal-trigger>
        <x-spire::button>Ver Termos</x-spire::button>
    </x-spire::modal-trigger>

    <x-spire::modal-content>
        <x-spire::modal-header>
            <x-spire::modal-title>Termos de Uso</x-spire::modal-title>
        </x-spire::modal-header>

        <x-spire::modal-body class="max-h-96 overflow-y-auto">
            <div class="prose prose-sm">
                <!-- Conteúdo longo com scroll -->
                <h3>1. Aceitação dos Termos</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>

                <h3>2. Uso do Serviço</h3>
                <p>Sed do eiusmod tempor incididunt ut labore et dolore...</p>

                <!-- Mais conteúdo... -->
            </div>
        </x-spire::modal-body>

        <x-spire::modal-footer>
            <x-spire::button variant="outline" @click="$dispatch('modal-close')">
                Recusar
            </x-spire::button>
            <x-spire::button @click="acceptTerms()">
                Aceitar Termos
            </x-spire::button>
        </x-spire::modal-footer>
    </x-spire::modal-content>
</x-spire::modal>
```

## Controle Programático

### Abrir/Fechar via JavaScript

```javascript
// Abrir modal
document.dispatchEvent(new CustomEvent('modal-open', {
    detail: { modalId: 'my-modal' }
}));

// Fechar modal
document.dispatchEvent(new CustomEvent('modal-close', {
    detail: { modalId: 'my-modal' }
}));
```

### Com Alpine.js

```blade
<div x-data="{ showModal: false }">
    <x-spire::button @click="showModal = true">Abrir Modal</x-spire::button>

    <x-spire::modal x-show="showModal" @modal-close.window="showModal = false">
        <x-spire::modal-content>
            <x-spire::modal-header>
                <x-spire::modal-title>Modal Controlado</x-spire::modal-title>
            </x-spire::modal-header>

            <x-spire::modal-body>
                <p>Conteúdo do modal.</p>
            </x-spire::modal-body>

            <x-spire::modal-footer>
                <x-spire::button @click="showModal = false">Fechar</x-spire::button>
            </x-spire::modal-footer>
        </x-spire::modal-content>
    </x-spire::modal>
</div>
```

## Acessibilidade

- **Foco**: Foco automático no modal aberto
- **Trap**: Navegação por teclado limitada ao modal
- **Escape**: Fecha com tecla Escape (configurável)
- **Backdrop**: Clicável para fechar (configurável)
- **ARIA**: Roles e labels apropriados
- **Screen Readers**: Anúncios adequados

## Animações

O modal inclui transições suaves:

- **Entrada**: Fade in + scale up
- **Saída**: Fade out + scale down
- **Backdrop**: Fade in/out
- **Duração**: 200ms para entrada, 150ms para saída

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/custom.css */
.spire-modal-backdrop {
    @apply bg-black/60 backdrop-blur-sm;
}

.spire-modal-content {
    @apply shadow-2xl border border-gray-200;
}

.dark .spire-modal-content {
    @apply bg-gray-800 border-gray-700;
}
```

### Modal Customizado

```blade
<x-spire::modal class="custom-modal">
    <x-spire::modal-trigger>
        <x-spire::button>Modal Customizado</x-spire::button>
    </x-spire::modal-trigger>

    <x-spire::modal-content class="bg-gradient-to-br from-purple-500 to-pink-500 text-white">
        <x-spire::modal-header>
            <x-spire::modal-title class="text-white">Título Colorido</x-spire::modal-title>
        </x-spire::modal-header>

        <x-spire::modal-body>
            <p>Conteúdo com fundo gradiente.</p>
        </x-spire::modal-body>
    </x-spire::modal-content>
</x-spire::modal>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `modal-open` | Modal foi aberto | `{ modalId?: string }` |
| `modal-close` | Modal foi fechado | `{ modalId?: string }` |
| `modal-before-open` | Antes de abrir | `{ modalId?: string }` |
| `modal-after-close` | Após fechar | `{ modalId?: string }` |

## Testes

**Cobertura**: 7 testes automatizados
- Inicialização e estrutura
- Abertura/fechamento
- Interações do usuário
- Acessibilidade
- Estados e propriedades

## Relacionados

- [Button](button.md) - Para triggers de modal
- [Drawer](drawer.md) - Alternativa lateral
- [Toast](toast.md) - Para notificações não-intrusivas