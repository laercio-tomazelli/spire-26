# Checkbox Component

O componente Checkbox fornece caixas de seleção individuais e em grupo com estados customizáveis.

## Uso Básico

```blade
<x-spire::checkbox label="Aceito os termos" />
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `label` | string | - | Texto do label |
| `value` | boolean\|string | - | Valor do checkbox |
| `checked` | boolean | `false` | Estado inicial |
| `disabled` | boolean | `false` | Checkbox desabilitado |
| `required` | boolean | `false` | Campo obrigatório |
| `indeterminate` | boolean | `false` | Estado indeterminado |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |
| `variant` | string | `"default"` | Variante: `default`, `error` |
| `description` | string | - | Descrição adicional |

## Exemplos

### Checkbox Simples

```blade
<x-spire::checkbox label="Receber newsletter" />
<x-spire::checkbox label="Aceito os termos de uso" required />
<x-spire::checkbox label="Concordo com a política de privacidade" />
```

### Estados

```blade
<!-- Marcado -->
<x-spire::checkbox label="Opção selecionada" checked />

<!-- Desmarcado -->
<x-spire::checkbox label="Opção não selecionada" />

<!-- Indeterminado -->
<x-spire::checkbox label="Opção parcial" indeterminate />

<!-- Desabilitado -->
<x-spire::checkbox label="Opção desabilitada" disabled />

<!-- Desabilitado e marcado -->
<x-spire::checkbox label="Opção desabilitada" disabled checked />
```

### Tamanhos

```blade
<x-spire::checkbox size="sm" label="Pequeno" />
<x-spire::checkbox size="md" label="Médio" />
<x-spire::checkbox size="lg" label="Grande" />
```

### Com Descrição

```blade
<x-spire::checkbox
    label="Backup automático"
    description="Fazer backup dos dados automaticamente todos os dias"
/>
```

## Grupo de Checkboxes

### Uso Básico

```blade
<x-spire::checkbox-group name="preferences" :options="$preferences" />
```

### Com Opções Inline

```blade
<x-spire::checkbox-group name="skills" label="Habilidades">
    <x-spire::checkbox value="php" label="PHP" />
    <x-spire::checkbox value="javascript" label="JavaScript" />
    <x-spire::checkbox value="python" label="Python" />
    <x-spire::checkbox value="java" label="Java" />
</x-spire::checkbox-group>
```

### Com Array de Opções

```blade
<x-spire::checkbox-group
    name="categories[]"
    :options="$categories"
    :value="$selectedCategories"
/>
```

## Propriedades do Checkbox Group

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `name` | string | - | Nome do campo |
| `label` | string | - | Label do grupo |
| `options` | array | `[]` | Array de opções |
| `value` | array | `[]` | Valores selecionados |
| `disabled` | boolean | `false` | Grupo desabilitado |
| `required` | boolean | `false` | Pelo menos uma opção obrigatória |
| `inline` | boolean | `false` | Layout horizontal |
| `size` | string | `"md"` | Tamanho dos checkboxes |
| `variant` | string | `"default"` | Variante: `default`, `error` |
| `description` | string | - | Descrição do grupo |

## Integração com Formulários

### Laravel Collective

```blade
{{ Form::spireCheckbox('newsletter', 'Receber newsletter', true) }}
{{ Form::spireCheckboxGroup('skills[]', $skills, $selectedSkills) }}
```

### Com Validação

```blade
<x-spire::form-group :error="$errors->first('terms')">
    <x-spire::checkbox
        name="terms"
        label="Aceito os termos de uso"
        required
        :variant="$errors->has('terms') ? 'error' : 'default'"
    />
</x-spire::form-group>
```

### Grupo com Validação

```blade
<x-spire::form-group label="Preferências" :error="$errors->first('preferences')">
    <x-spire::checkbox-group
        name="preferences[]"
        :options="$preferences"
        :value="old('preferences', [])"
        required
        :variant="$errors->has('preferences') ? 'error' : 'default'"
    />
</x-spire::form-group>
```

## Integração com Alpine.js

### Seleção Múltipla com Controle

```blade
<div x-data="checkboxController">
    <x-spire::checkbox
        x-model="selectAll"
        label="Selecionar todos"
        @change="toggleAll"
    />

    <x-spire::checkbox-group class="mt-3">
        <template x-for="item in items" :key="item.id">
            <x-spire::checkbox
                :value="item.id"
                :label="item.name"
                :checked="selected.includes(item.id)"
                @change="updateSelection(item.id, $event.target.checked)"
            />
        </template>
    </x-spire::checkbox-group>

    <p class="mt-3 text-sm text-gray-600">
        Selecionados: <span x-text="selected.length"></span>
    </p>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('checkboxController', () => ({
        items: @json($items),
        selected: [],
        selectAll: false,

        toggleAll() {
            if (this.selectAll) {
                this.selected = this.items.map(item => item.id);
            } else {
                this.selected = [];
            }
        },

        updateSelection(id, checked) {
            if (checked) {
                this.selected.push(id);
            } else {
                this.selected = this.selected.filter(item => item !== id);
            }
            this.selectAll = this.selected.length === this.items.length;
        }
    }));
});
</script>
```

### Filtros Dinâmicos

```blade
<div x-data="filterController">
    <h3 class="font-semibold mb-3">Filtros</h3>

    <x-spire::checkbox-group>
        <x-spire::checkbox
            x-model="filters.category"
            value="electronics"
            label="Eletrônicos"
        />
        <x-spire::checkbox
            x-model="filters.category"
            value="books"
            label="Livros"
        />
        <x-spire::checkbox
            x-model="filters.category"
            value="clothing"
            label="Roupas"
        />
    </x-spire::checkbox-group>

    <div class="mt-4">
        <x-spire::checkbox
            x-model="filters.inStock"
            label="Apenas produtos em estoque"
        />
    </div>

    <div class="mt-4">
        <x-spire::checkbox
            x-model="filters.onSale"
            label="Apenas produtos em promoção"
        />
    </div>

    <x-spire::button @click="applyFilters" class="mt-4">
        Aplicar Filtros
    </x-spire::button>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('filterController', () => ({
        filters: {
            category: [],
            inStock: false,
            onSale: false
        },

        applyFilters() {
            // Aplicar filtros na lista de produtos
            console.log('Filtros aplicados:', this.filters);
        }
    }));
});
</script>
```

### Validação Condicional

```blade
<div x-data="conditionalValidation">
    <x-spire::checkbox
        x-model="hasAllergies"
        label="Tenho alergias alimentares"
    />

    <div x-show="hasAllergies" x-transition class="mt-3">
        <x-spire::form-group label="Quais alergias?">
            <x-spire::checkbox-group
                x-model="allergies"
                :options="$allergyOptions"
                required
            />
        </x-spire::form-group>
    </div>

    <x-spire::button
        @click="submitForm"
        :disabled="!isValid"
        class="mt-4"
    >
        Enviar
    </x-spire::button>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('conditionalValidation', () => ({
        hasAllergies: false,
        allergies: [],

        get isValid() {
            if (!this.hasAllergies) return true;
            return this.allergies.length > 0;
        },

        submitForm() {
            if (this.isValid) {
                // Enviar formulário
                console.log('Formulário válido');
            }
        }
    }));
});
</script>
```

### Checkbox de Confirmação

```blade
<div x-data="confirmationCheckbox">
    <x-spire::checkbox
        x-model="confirmed"
        label="Confirmo que li e aceito os termos"
        required
        :variant="!confirmed && showError ? 'error' : 'default'"
    />

    <div x-show="!confirmed && showError" class="text-red-600 text-sm mt-1">
        Você deve aceitar os termos para continuar
    </div>

    <x-spire::button
        @click="proceed"
        :disabled="!confirmed"
        class="mt-4"
    >
        Continuar
    </x-spire::button>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('confirmationCheckbox', () => ({
        confirmed: false,
        showError: false,

        proceed() {
            if (!this.confirmed) {
                this.showError = true;
                return;
            }
            // Prosseguir com a ação
            console.log('Prosseguindo...');
        }
    }));
});
</script>
```

## Acessibilidade

- **Labels**: Sempre forneça labels descritivos
- **Estados**: `aria-checked`, `aria-disabled`
- **Grupos**: `role="group"` com `aria-labelledby`
- **Navegação**: Tab order correto
- **Leitores de Tela**: Anúncios adequados
- **Indeterminado**: Suporte completo

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/checkbox-custom.css */
.spire-checkbox {
    @apply flex items-center space-x-2 cursor-pointer;
}

.spire-checkbox__input {
    @apply w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500;
}

.spire-checkbox__label {
    @apply text-gray-700 cursor-pointer;
}

.spire-checkbox--checked .spire-checkbox__input {
    @apply bg-blue-600 border-blue-600;
}

.spire-checkbox--error .spire-checkbox__input {
    @apply border-red-500;
}
```

### Checkbox Customizado

```blade
<x-spire::checkbox
    class="custom-checkbox"
    label="Checkbox customizado"
/>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `change` | Estado alterado | `{ checked: boolean }` |
| `focus` | Campo ganhou foco | - |
| `blur` | Campo perdeu foco | - |

## Performance

- **Lazy Rendering**: Para grupos grandes
- **Event Delegation**: Eventos otimizados
- **State Management**: Controle eficiente de estado
- **Memory**: Limpeza adequada

## Testes

**Cobertura**: 16 testes automatizados
- Estados e propriedades
- Interações do usuário
- Grupos e validação
- Acessibilidade
- Estados edge case

## Relacionados

- [Radio](radio.md) - Para seleção única
- [Select](select.md) - Para seleção dropdown
- [Form Group](form-group.md) - Para labels e validação
- [Toggle](toggle.md) - Para estados booleanos