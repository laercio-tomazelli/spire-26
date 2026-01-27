# Tooltip Component

O componente Tooltip fornece dicas contextuais que aparecem ao passar o mouse sobre elementos.

## Uso Básico

```blade
<x-spire::tooltip content="Esta é uma dica">
    <x-spire::button>Passe o mouse aqui</x-spire::button>
</x-spire::tooltip>
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `content` | string | - | Conteúdo da dica |
| `placement` | string | `"top"` | Posicionamento: `top`, `bottom`, `left`, `right` |
| `delay` | number | `300` | Atraso em ms para mostrar |
| `hideDelay` | number | `0` | Atraso em ms para esconder |
| `disabled` | boolean | `false` | Tooltip desabilitado |
| `interactive` | boolean | `false` | Permite interação com o tooltip |
| `maxWidth` | string | `"200px"` | Largura máxima |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |
| `variant` | string | `"default"` | Variante: `default`, `dark`, `light` |

## Exemplos

### Tooltip Básico

```blade
<x-spire::tooltip content="Salvar alterações">
    <x-spire::button>
        <x-spire::icon name="save" />
    </x-spire::button>
</x-spire::tooltip>
```

### Posicionamentos

```blade
<x-spire::tooltip content="Tooltip no topo" placement="top">
    <x-spire::button>Topo</x-spire::button>
</x-spire::tooltip>

<x-spire::tooltip content="Tooltip na direita" placement="right">
    <x-spire::button>Direita</x-spire::button>
</x-spire::tooltip>

<x-spire::tooltip content="Tooltip embaixo" placement="bottom">
    <x-spire::button>Baixo</x-spire::button>
</x-spire::tooltip>

<x-spire::tooltip content="Tooltip na esquerda" placement="left">
    <x-spire::button>Esquerda</x-spire::button>
</x-spire::tooltip>
```

### Tamanhos

```blade
<x-spire::tooltip content="Tooltip pequeno" size="sm">
    <x-spire::button size="sm">SM</x-spire::button>
</x-spire::tooltip>

<x-spire::tooltip content="Tooltip médio" size="md">
    <x-spire::button size="md">MD</x-spire::button>
</x-spire::tooltip>

<x-spire::tooltip content="Tooltip grande" size="lg">
    <x-spire::button size="lg">LG</x-spire::button>
</x-spire::tooltip>
```

### Variantes

```blade
<x-spire::tooltip content="Tooltip padrão" variant="default">
    <x-spire::button>Padrão</x-spire::button>
</x-spire::tooltip>

<x-spire::tooltip content="Tooltip escuro" variant="dark">
    <x-spire::button>Escuro</x-spire::button>
</x-spire::tooltip>

<x-spire::tooltip content="Tooltip claro" variant="light">
    <x-spire::button>Claro</x-spire::button>
</x-spire::tooltip>
```

### Com HTML

```blade
<x-spire::tooltip>
    <x-slot:content>
        <div class="text-center">
            <strong>Atalho:</strong><br>
            <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl + S</kbd>
        </div>
    </x-slot:content>

    <x-spire::button>Salvar</x-spire::button>
</x-spire::tooltip>
```

### Tooltip Interativo

```blade
<x-spire::tooltip content="Clique para mais informações" interactive>
    <x-spire::button>Info</x-spire::button>
</x-spire::tooltip>
```

### Em Formulários

```blade
<x-spire::form-group label="Email">
    <x-spire::tooltip content="Será usado para login e notificações">
        <x-spire::input type="email" name="email" />
    </x-spire::tooltip>
</x-spire::form-group>
```

### Com Ícones

```blade
<div class="flex space-x-2">
    <x-spire::tooltip content="Editar">
        <x-spire::button variant="ghost" size="sm">
            <x-spire::icon name="pencil" />
        </x-spire::button>
    </x-spire::tooltip>

    <x-spire::tooltip content="Excluir">
        <x-spire::button variant="ghost" size="sm">
            <x-spire::icon name="trash" />
        </x-spire::button>
    </x-spire::tooltip>

    <x-spire::tooltip content="Compartilhar">
        <x-spire::button variant="ghost" size="sm">
            <x-spire::icon name="share" />
        </x-spire::button>
    </x-spire::tooltip>
</div>
```

## Integração com Alpine.js

### Tooltip Dinâmico

```blade
<div x-data="dynamicTooltip">
    <x-spire::tooltip :content="currentTip" placement="top">
        <x-spire::button @mouseenter="updateTip('Novo tooltip!')">
            Passe o mouse
        </x-spire::button>
    </x-spire::tooltip>

    <p class="mt-2 text-sm text-gray-600">
        Tooltip atual: <span x-text="currentTip"></span>
    </p>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dynamicTooltip', () => ({
        currentTip: 'Tooltip inicial',

        updateTip(newTip) {
            this.currentTip = newTip;
        }
    }));
});
</script>
```

### Tooltip Condicional

```blade
<div x-data="conditionalTooltip">
    <x-spire::tooltip
        :content="isDisabled ? 'Este botão está desabilitado' : 'Clique para continuar'"
        :disabled="!showTooltip"
    >
        <x-spire::button :disabled="isDisabled">
            Botão Condicional
        </x-spire::button>
    </x-spire::tooltip>

    <div class="mt-4 space-x-2">
        <x-spire::button @click="isDisabled = !isDisabled">
            Toggle Disabled
        </x-spire::button>
        <x-spire::button @click="showTooltip = !showTooltip">
            Toggle Tooltip
        </x-spire::button>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('conditionalTooltip', () => ({
        isDisabled: false,
        showTooltip: true
    }));
});
</script>
```

### Tooltip com Dados

```blade
<div x-data="dataTooltip">
    <div class="space-y-2">
        <template x-for="user in users" :key="user.id">
            <x-spire::tooltip>
                <x-slot:content>
                    <div class="text-center">
                        <img :src="user.avatar" class="w-12 h-12 rounded-full mx-auto mb-2" />
                        <div class="font-semibold" x-text="user.name"></div>
                        <div class="text-sm text-gray-600" x-text="user.email"></div>
                        <div class="text-xs text-gray-500 mt-1" x-text="user.role"></div>
                    </div>
                </x-slot:content>

                <div class="flex items-center space-x-2 p-2 border rounded cursor-pointer">
                    <img :src="user.avatar" class="w-8 h-8 rounded-full" />
                    <span x-text="user.name"></span>
                </div>
            </x-spire::tooltip>
        </template>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dataTooltip', () => ({
        users: @json($users)
    }));
});
</script>
```

### Tooltip de Validação

```blade
<div x-data="validationTooltip">
    <x-spire::form-group label="Senha">
        <x-spire::tooltip
            :content="passwordError"
            :variant="passwordError ? 'error' : 'default'"
            placement="right"
            :disabled="!passwordError"
        >
            <x-spire::input
                type="password"
                x-model="password"
                @blur="validatePassword"
            />
        </x-spire::tooltip>
    </x-spire::form-group>

    <div class="mt-4">
        <x-spire::button @click="checkStrength">
            Verificar Força
        </x-spire::button>
        <span x-show="strength" class="ml-2" x-text="strength"></span>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('validationTooltip', () => ({
        password: '',
        passwordError: '',
        strength: '',

        validatePassword() {
            if (this.password.length < 8) {
                this.passwordError = 'A senha deve ter pelo menos 8 caracteres';
            } else {
                this.passwordError = '';
            }
        },

        checkStrength() {
            const length = this.password.length;
            if (length < 6) this.strength = 'Fraca';
            else if (length < 10) this.strength = 'Média';
            else this.strength = 'Forte';
        }
    }));
});
</script>
```

## Acessibilidade

- **ARIA**: `aria-describedby` para leitores de tela
- **Foco**: Funciona com foco por teclado
- **Delay**: Tempo adequado para usuários
- **Conteúdo**: Texto descritivo claro
- **Navegação**: Não interfere na navegação

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/tooltip-custom.css */
.spire-tooltip {
    @apply relative inline-block;
}

.spire-tooltip__content {
    @apply absolute z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-md shadow-lg;
}

.spire-tooltip__content--light {
    @apply bg-white text-gray-900 border border-gray-200;
}

.spire-tooltip__content--dark {
    @apply bg-gray-900 text-white;
}

.spire-tooltip__arrow {
    @apply absolute w-2 h-2 bg-current transform rotate-45;
}
```

### Tooltip Customizado

```blade
<x-spire::tooltip class="custom-tooltip" content="Tooltip customizado">
    <x-spire::button>Custom</x-spire::button>
</x-spire::tooltip>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `show` | Tooltip exibido | - |
| `hide` | Tooltip ocultado | - |
| `mouseenter` | Mouse entrou no trigger | - |
| `mouseleave` | Mouse saiu do trigger | - |

## Performance

- **Lazy Loading**: Só carrega quando necessário
- **Debounced Events**: Eventos otimizados
- **Memory Management**: Limpeza adequada
- **Positioning**: Cálculo eficiente

## Testes

**Cobertura**: 8 testes automatizados
- Renderização e posicionamento
- Interações do usuário
- Estados e propriedades
- Acessibilidade
- Performance

## Relacionados

- [Dropdown](dropdown.md) - Para menus interativos
- [Modal](modal.md) - Para conteúdo maior
- [Button](button.md) - Para elementos com dicas
- [Icon](icon.md) - Para ícones com explicações