# Button Component

O componente Button fornece botões flexíveis e acessíveis com múltiplas variantes visuais e estados funcionais.

## Uso Básico

```blade
<x-spire::button>Botão Padrão</x-spire::button>
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `variant` | string | `"primary"` | Estilo visual: `primary`, `secondary`, `outline`, `ghost`, `danger` |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |
| `disabled` | boolean | `false` | Desabilita o botão |
| `loading` | boolean | `false` | Mostra estado de carregamento |
| `type` | string | `"button"` | Tipo HTML: `button`, `submit`, `reset` |
| `href` | string | - | Converte em link (opcional) |

## Variantes

### Primary (Padrão)
```blade
<x-spire::button variant="primary">Botão Primário</x-spire::button>
```

### Secondary
```blade
<x-spire::button variant="secondary">Botão Secundário</x-spire::button>
```

### Outline
```blade
<x-spire::button variant="outline">Botão Outline</x-spire::button>
```

### Ghost
```blade
<x-spire::button variant="ghost">Botão Ghost</x-spire::button>
```

### Danger
```blade
<x-spire::button variant="danger">Botão Perigoso</x-spire::button>
```

## Tamanhos

### Small
```blade
<x-spire::button size="sm">Botão Pequeno</x-spire::button>
```

### Medium (Padrão)
```blade
<x-spire::button size="md">Botão Médio</x-spire::button>
```

### Large
```blade
<x-spire::button size="lg">Botão Grande</x-spire::button>
```

## Estados

### Desabilitado
```blade
<x-spire::button disabled>Botão Desabilitado</x-spire::button>
```

### Carregando
```blade
<x-spire::button loading>Botão Carregando</x-spire::button>
```

## Com Ícones

```blade
<x-spire::button>
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    Salvar
</x-spire::button>
```

## Com Links

```blade
<x-spire::button href="/dashboard">Ir para Dashboard</x-spire::button>
```

## Exemplos Avançados

### Botão de Loading com Alpine.js

```blade
<div x-data="{ loading: false }" class="space-y-4">
    <x-spire::button
        :loading="loading"
        @click="loading = true; setTimeout(() => loading = false, 2000)"
    >
        <span x-show="!loading">Processar</span>
        <span x-show="loading">Processando...</span>
    </x-spire::button>
</div>
```

### Grupo de Botões

```blade
<div class="flex space-x-2">
    <x-spire::button variant="outline" size="sm">Cancelar</x-spire::button>
    <x-spire::button variant="primary" size="sm">Salvar</x-spire::button>
</div>
```

### Botão Flutuante (FAB)

```blade
<x-spire::button
    variant="primary"
    size="lg"
    class="fixed bottom-6 right-6 rounded-full shadow-lg hover:shadow-xl transition-shadow"
>
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
</x-spire::button>
```

## Acessibilidade

O componente Button segue as melhores práticas de acessibilidade:

- **Semântica**: Usa `<button>` ou `<a>` apropriadamente
- **Teclado**: Totalmente navegável por Tab
- **Leitores de Tela**: Anúncios adequados para estados
- **Foco**: Indicador visual claro
- **ARIA**: Estados apropriados quando necessário

## Estilização Customizada

### Classes CSS Customizadas

```blade
<x-spire::button class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600">
    Botão Customizado
</x-spire::button>
```

### Tema Escuro

```blade
<x-spire::button class="dark:bg-gray-800 dark:border-gray-600 dark:text-white">
    Botão Tema Escuro
</x-spire::button>
```

## API JavaScript

```javascript
// Acesso programático
const button = document.querySelector('[data-spire-button]');
button.click(); // Simula clique
button.disabled = true; // Desabilita
```

## Testes

O componente inclui cobertura completa de testes:

```bash
npm run test:run resources/js/spire/test/Button.test.ts
```

**Cobertura**: 6 testes automatizados
- Inicialização correta
- Variantes visuais
- Estados (disabled, loading)
- Eventos de clique
- Acessibilidade

## Relacionados

- [Modal](modal.md) - Para ações que abrem modais
- [Dropdown](dropdown.md) - Para menus dropdown
- [FormValidator](formvalidator.md) - Para validação de formulários