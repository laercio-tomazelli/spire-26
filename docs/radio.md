# Radio Component

O componente Radio fornece botões de rádio para seleção única com agrupamento e validação.

## Uso Básico

```blade
<x-spire::radio name="gender" value="male" label="Masculino" />
<x-spire::radio name="gender" value="female" label="Feminino" />
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `name` | string | - | Nome do grupo |
| `value` | string\|number | - | Valor do radio |
| `label` | string | - | Texto do label |
| `checked` | boolean | `false` | Estado inicial |
| `disabled` | boolean | `false` | Radio desabilitado |
| `required` | boolean | `false` | Campo obrigatório |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |
| `variant` | string | `"default"` | Variante: `default`, `error` |
| `description` | string | - | Descrição adicional |

## Exemplos

### Grupo Básico

```blade
<x-spire::radio name="payment" value="credit" label="Cartão de Crédito" />
<x-spire::radio name="payment" value="debit" label="Cartão de Débito" />
<x-spire::radio name="payment" value="boleto" label="Boleto Bancário" />
```

### Com Seleção Prévia

```blade
<x-spire::radio name="size" value="small" label="Pequeno" />
<x-spire::radio name="size" value="medium" label="Médio" checked />
<x-spire::radio name="size" value="large" label="Grande" />
```

### Estados

```blade
<!-- Normal -->
<x-spire::radio name="option" value="1" label="Opção 1" />

<!-- Selecionado -->
<x-spire::radio name="option" value="2" label="Opção 2" checked />

<!-- Desabilitado -->
<x-spire::radio name="option" value="3" label="Opção 3" disabled />

<!-- Desabilitado e selecionado -->
<x-spire::radio name="option" value="4" label="Opção 4" disabled checked />
```

### Tamanhos

```blade
<x-spire::radio name="test" size="sm" value="small" label="Pequeno" />
<x-spire::radio name="test" size="md" value="medium" label="Médio" />
<x-spire::radio name="test" size="lg" value="large" label="Grande" />
```

### Com Descrição

```blade
<x-spire::radio
    name="plan"
    value="basic"
    label="Plano Básico"
    description="Ideal para pequenos negócios"
/>
<x-spire::radio
    name="plan"
    value="premium"
    label="Plano Premium"
    description="Recursos avançados para empresas"
/>
```

## Grupo de Radio

### Uso Básico

```blade
<x-spire::radio-group name="priority" :options="$priorities" />
```

### Com Opções Inline

```blade
<x-spire::radio-group name="status" label="Status do Pedido">
    <x-spire::radio value="pending" label="Pendente" />
    <x-spire::radio value="processing" label="Processando" />
    <x-spire::radio value="completed" label="Concluído" />
    <x-spire::radio value="cancelled" label="Cancelado" />
</x-spire::radio-group>
```

### Com Array de Opções

```blade
<x-spire::radio-group
    name="category"
    :options="$categories"
    :value="$selectedCategory"
/>
```

## Propriedades do Radio Group

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `name` | string | - | Nome do campo |
| `label` | string | - | Label do grupo |
| `options` | array | `[]` | Array de opções |
| `value` | string\|number | - | Valor selecionado |
| `disabled` | boolean | `false` | Grupo desabilitado |
| `required` | boolean | `false` | Seleção obrigatória |
| `inline` | boolean | `false` | Layout horizontal |
| `size` | string | `"md"` | Tamanho dos radios |
| `variant` | string | `"default"` | Variante: `default`, `error` |
| `description` | string | - | Descrição do grupo |

## Integração com Formulários

### Laravel Collective

```blade
{{ Form::spireRadio('gender', 'male', 'Masculino', true) }}
{{ Form::spireRadio('gender', 'female', 'Feminino') }}

{{ Form::spireRadioGroup('priority', $priorities, 'medium') }}
```

### Com Validação

```blade
<x-spire::form-group label="Gênero" :error="$errors->first('gender')">
    <x-spire::radio-group
        name="gender"
        :options="$genders"
        :value="old('gender')"
        required
        :variant="$errors->has('gender') ? 'error' : 'default'"
    />
</x-spire::form-group>
```

### Formulário Completo

```blade
<form method="POST" action="/register">
    @csrf

    <x-spire::form-group label="Nome">
        <x-spire::input name="name" required />
    </x-spire::form-group>

    <x-spire::form-group label="Gênero" :error="$errors->first('gender')">
        <x-spire::radio-group
            name="gender"
            :options="['male' => 'Masculino', 'female' => 'Feminino', 'other' => 'Outro']"
            :value="old('gender')"
            required
            :variant="$errors->has('gender') ? 'error' : 'default'"
        />
    </x-spire::form-group>

    <x-spire::form-group label="Newsletter">
        <x-spire::checkbox name="newsletter" label="Receber newsletter" />
    </x-spire::form-group>

    <x-spire::button type="submit">Cadastrar</x-spire::button>
</form>
```

## Integração com Alpine.js

### Seleção Dinâmica

```blade
<div x-data="dynamicRadio">
    <x-spire::radio-group
        name="plan"
        :options="$plans"
        x-model="selectedPlan"
        @change="updatePrice"
    />

    <div x-show="selectedPlan" class="mt-4 p-4 bg-blue-50 rounded">
        <h3 class="font-semibold" x-text="getPlanName()"></h3>
        <p class="text-2xl font-bold text-blue-600" x-text="getPlanPrice()"></p>
        <ul class="mt-2 text-sm">
            <template x-for="feature in getPlanFeatures()" :key="feature">
                <li>✓ <span x-text="feature"></span></li>
            </template>
        </ul>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dynamicRadio', () => ({
        selectedPlan: '',
        plans: @json($plans),

        updatePrice() {
            console.log('Plano selecionado:', this.selectedPlan);
        },

        getPlanName() {
            const plan = this.plans.find(p => p.value === this.selectedPlan);
            return plan ? plan.label : '';
        },

        getPlanPrice() {
            const plan = this.plans.find(p => p.value === this.selectedPlan);
            return plan ? `R$ ${plan.price}` : '';
        },

        getPlanFeatures() {
            const plan = this.plans.find(p => p.value === this.selectedPlan);
            return plan ? plan.features : [];
        }
    }));
});
</script>
```

### Validação Visual

```blade
<div x-data="radioValidation">
    <x-spire::form-group label="Método de Pagamento" :error="errorMessage">
        <x-spire::radio-group
            name="payment_method"
            :options="$paymentMethods"
            x-model="selectedMethod"
            required
            :variant="errorMessage ? 'error' : 'default'"
        />
    </x-spire::form-group>

    <div x-show="selectedMethod === 'credit_card'" x-transition class="mt-4">
        <x-spire::form-group label="Número do Cartão">
            <x-spire::input
                name="card_number"
                mask="9999 9999 9999 9999"
                placeholder="0000 0000 0000 0000"
            />
        </x-spire::form-group>
    </div>

    <x-spire::button @click="validateAndSubmit" class="mt-4">
        Finalizar Compra
    </x-spire::button>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('radioValidation', () => ({
        selectedMethod: '',
        errorMessage: '',

        validateAndSubmit() {
            if (!this.selectedMethod) {
                this.errorMessage = 'Selecione um método de pagamento';
                return;
            }
            this.errorMessage = '';
            // Prosseguir com o checkout
            console.log('Método selecionado:', this.selectedMethod);
        }
    }));
});
</script>
```

### Radio com Imagens

```blade
<div x-data="imageRadio">
    <div class="grid grid-cols-3 gap-4">
        <template x-for="theme in themes" :key="theme.value">
            <label class="cursor-pointer">
                <x-spire::radio
                    name="theme"
                    :value="theme.value"
                    x-model="selectedTheme"
                    class="sr-only"
                />
                <div
                    class="border-2 rounded-lg p-4 transition-all"
                    :class="selectedTheme === theme.value ? 'border-blue-500 bg-blue-50' : 'border-gray-200'"
                >
                    <img :src="theme.image" :alt="theme.label" class="w-full h-20 object-cover rounded mb-2" />
                    <h3 class="font-semibold text-center" x-text="theme.label"></h3>
                </div>
            </label>
        </template>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('imageRadio', () => ({
        selectedTheme: '',
        themes: @json($themes)
    }));
});
</script>
```

### Pesquisa de Satisfação

```blade
<div x-data="satisfactionSurvey">
    <h3 class="text-lg font-semibold mb-4">Como você avalia nossa atendimento?</h3>

    <x-spire::radio-group
        name="rating"
        x-model="rating"
        inline
        class="flex justify-center space-x-4"
    >
        <x-spire::radio value="1" label="1" />
        <x-spire::radio value="2" label="2" />
        <x-spire::radio value="3" label="3" />
        <x-spire::radio value="4" label="4" />
        <x-spire::radio value="5" label="5" />
    </x-spire::radio-group>

    <div class="flex justify-between text-sm text-gray-500 mt-2">
        <span>Muito Insatisfeito</span>
        <span>Muito Satisfeito</span>
    </div>

    <div x-show="rating" class="mt-4">
        <x-spire::textarea
            placeholder="Comentários adicionais (opcional)"
            rows="3"
            x-model="comments"
        />
    </div>

    <x-spire::button
        @click="submitSurvey"
        :disabled="!rating"
        class="mt-4"
    >
        Enviar Avaliação
    </x-spire::button>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('satisfactionSurvey', () => ({
        rating: '',
        comments: '',

        submitSurvey() {
            // Enviar avaliação
            console.log('Avaliação:', { rating: this.rating, comments: this.comments });
        }
    }));
});
</script>
```

## Acessibilidade

- **Labels**: Sempre forneça labels descritivos
- **Grupos**: `role="radiogroup"` com `aria-labelledby`
- **Estados**: `aria-checked`, `aria-disabled`
- **Navegação**: Arrow keys para navegação
- **Leitores de Tela**: Anúncios adequados
- **Required**: Indicação clara de obrigatoriedade

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/radio-custom.css */
.spire-radio {
    @apply flex items-center space-x-2 cursor-pointer;
}

.spire-radio__input {
    @apply w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500;
}

.spire-radio__label {
    @apply text-gray-700 cursor-pointer;
}

.spire-radio--checked .spire-radio__input {
    @apply bg-blue-600 border-blue-600;
}

.spire-radio--error .spire-radio__input {
    @apply border-red-500;
}
```

### Radio Customizado

```blade
<x-spire::radio
    class="custom-radio"
    name="option"
    value="custom"
    label="Opção customizada"
/>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `change` | Seleção alterada | `{ value: string\|number }` |
| `focus` | Campo ganhou foco | - |
| `blur` | Campo perdeu foco | - |

## Performance

- **Lazy Rendering**: Para grupos grandes
- **Event Delegation**: Eventos otimizados
- **State Sync**: Sincronização eficiente
- **Memory**: Limpeza adequada

## Testes

**Cobertura**: 14 testes automatizados
- Estados e propriedades
- Interações do usuário
- Grupos e validação
- Acessibilidade
- Navegação por teclado

## Relacionados

- [Checkbox](checkbox.md) - Para seleção múltipla
- [Select](select.md) - Para seleção dropdown
- [Form Group](form-group.md) - Para labels e validação
- [Toggle](toggle.md) - Para estados booleanos