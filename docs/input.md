# Input Component

O componente Input fornece campos de entrada flexíveis com validação, máscaras e vários tipos de entrada.

## Uso Básico

```blade
<x-spire::input placeholder="Digite seu nome" />
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `type` | string | `"text"` | Tipo do input: `text`, `email`, `password`, `number`, `tel`, `url`, `search` |
| `placeholder` | string | - | Placeholder do campo |
| `value` | string\|number | - | Valor do campo |
| `disabled` | boolean | `false` | Campo desabilitado |
| `readonly` | boolean | `false` | Campo somente leitura |
| `required` | boolean | `false` | Campo obrigatório |
| `autofocus` | boolean | `false` | Foco automático |
| `maxlength` | number | - | Máximo de caracteres |
| `minlength` | number | - | Mínimo de caracteres |
| `pattern` | string | - | Padrão regex para validação |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |
| `variant` | string | `"default"` | Variante: `default`, `error`, `success` |
| `icon` | string | - | Ícone (nome do Heroicon) |
| `iconPosition` | string | `"left"` | Posição do ícone: `left`, `right` |
| `mask` | string | - | Máscara de entrada |
| `unmask` | boolean | `false` | Remove máscara no valor |

## Exemplos

### Tipos Básicos

```blade
<!-- Texto -->
<x-spire::input type="text" placeholder="Nome completo" />

<!-- Email -->
<x-spire::input type="email" placeholder="email@exemplo.com" />

<!-- Senha -->
<x-spire::input type="password" placeholder="Digite sua senha" />

<!-- Número -->
<x-spire::input type="number" placeholder="Idade" min="0" max="120" />

<!-- Telefone -->
<x-spire::input type="tel" placeholder="(11) 99999-9999" />

<!-- URL -->
<x-spire::input type="url" placeholder="https://exemplo.com" />

<!-- Busca -->
<x-spire::input type="search" placeholder="Buscar..." />
```

### Tamanhos

```blade
<x-spire::input size="sm" placeholder="Pequeno" />
<x-spire::input size="md" placeholder="Médio" />
<x-spire::input size="lg" placeholder="Grande" />
```

### Estados

```blade
<!-- Normal -->
<x-spire::input placeholder="Campo normal" />

<!-- Sucesso -->
<x-spire::input variant="success" placeholder="Campo válido" />

<!-- Erro -->
<x-spire::input variant="error" placeholder="Campo com erro" />

<!-- Desabilitado -->
<x-spire::input disabled placeholder="Campo desabilitado" />

<!-- Somente leitura -->
<x-spire::input readonly value="Campo somente leitura" />
```

### Com Ícones

```blade
<!-- Ícone à esquerda -->
<x-spire::input icon="user" placeholder="Nome de usuário" />

<!-- Ícone à direita -->
<x-spire::input icon="search" icon-position="right" placeholder="Buscar" />

<!-- Ícone de envelope -->
<x-spire::input icon="envelope" type="email" placeholder="Email" />
```

### Com Máscaras

```blade
<!-- CPF -->
<x-spire::input mask="999.999.999-99" placeholder="000.000.000-00" />

<!-- CNPJ -->
<x-spire::input mask="99.999.999/9999-99" placeholder="00.000.000/0000-00" />

<!-- Telefone -->
<x-spire::input mask="(99) 99999-9999" placeholder="(00) 00000-0000" />

<!-- CEP -->
<x-spire::input mask="99999-999" placeholder="00000-000" />

<!-- Data -->
<x-spire::input mask="99/99/9999" placeholder="DD/MM/AAAA" />

<!-- Cartão de Crédito -->
<x-spire::input mask="9999 9999 9999 9999" placeholder="0000 0000 0000 0000" />
```

### Com Validação

```blade
<!-- Campo obrigatório -->
<x-spire::input required placeholder="Campo obrigatório" />

<!-- Comprimento mínimo -->
<x-spire::input minlength="3" placeholder="Mínimo 3 caracteres" />

<!-- Comprimento máximo -->
<x-spire::input maxlength="50" placeholder="Máximo 50 caracteres" />

<!-- Padrão regex -->
<x-spire::input pattern="[A-Za-z]+" placeholder="Apenas letras" />
```

## Integração com Formulários

### Laravel Collective

```blade
{{ Form::spireInput('name', 'Nome', ['required' => true]) }}
{{ Form::spireInput('email', 'Email', ['type' => 'email']) }}
{{ Form::spireInput('password', 'Senha', ['type' => 'password']) }}
```

### Com Labels

```blade
<x-spire::form-group label="Nome Completo">
    <x-spire::input name="name" required />
</x-spire::form-group>

<x-spire::form-group label="Email" description="Será usado para login">
    <x-spire::input type="email" name="email" required />
</x-spire::form-group>
```

### Com Erros

```blade
<x-spire::form-group label="Email" :error="$errors->first('email')">
    <x-spire::input
        type="email"
        name="email"
        :value="old('email')"
        :variant="$errors->has('email') ? 'error' : 'default'"
    />
</x-spire::form-group>
```

## Integração com Alpine.js

### Busca Reativa

```blade
<div x-data="searchComponent">
    <x-spire::input
        x-model="query"
        @input.debounce.300ms="search"
        icon="search"
        icon-position="right"
        placeholder="Buscar produtos..."
    />

    <div x-show="results.length > 0" class="mt-4">
        <ul>
            <template x-for="result in results" :key="result.id">
                <li x-text="result.name"></li>
            </template>
        </ul>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('searchComponent', () => ({
        query: '',
        results: [],

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }

            const response = await fetch(`/api/search?q=${this.query}`);
            this.results = await response.json();
        }
    }));
});
</script>
```

### Validação em Tempo Real

```blade
<div x-data="formValidation">
    <x-spire::form-group label="Email" :error="emailError">
        <x-spire::input
            x-model="email"
            @blur="validateEmail"
            type="email"
            :variant="emailError ? 'error' : (emailValid ? 'success' : 'default')"
        />
    </x-spire::form-group>

    <p x-show="emailValid" class="text-green-600 text-sm">Email válido!</p>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('formValidation', () => ({
        email: '',
        emailError: '',
        emailValid: false,

        validateEmail() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!this.email) {
                this.emailError = 'Email é obrigatório';
                this.emailValid = false;
            } else if (!emailRegex.test(this.email)) {
                this.emailError = 'Email inválido';
                this.emailValid = false;
            } else {
                this.emailError = '';
                this.emailValid = true;
            }
        }
    }));
});
</script>
```

### Máscara Dinâmica

```blade
<div x-data="phoneInput">
    <x-spire::input
        x-model="phone"
        :mask="phone.length <= 14 ? '(99) 9999-9999' : '(99) 99999-9999'"
        placeholder="(00) 0000-0000"
        type="tel"
    />

    <p class="text-sm text-gray-600 mt-1" x-text="formatPhone()"></p>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('phoneInput', () => ({
        phone: '',

        formatPhone() {
            // Remove máscara para exibir formato limpo
            return this.phone.replace(/\D/g, '').replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3');
        }
    }));
});
</script>
```

## Acessibilidade

- **Labels**: Sempre use com `<x-spire::form-group>` ou `aria-label`
- **Descrições**: Use `aria-describedby` para instruções
- **Erros**: Anuncie erros com `aria-live`
- **Estados**: `aria-disabled`, `aria-readonly`
- **Navegação**: Tab order correto
- **Leitores de Tela**: Anúncios adequados

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/input-custom.css */
.spire-input {
    @apply border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500;
}

.spire-input--error {
    @apply border-red-500 focus:ring-red-500;
}

.spire-input--success {
    @apply border-green-500 focus:ring-green-500;
}

.spire-input--disabled {
    @apply bg-gray-100 cursor-not-allowed;
}
```

### Input Customizado

```blade
<x-spire::input
    class="custom-input"
    placeholder="Campo customizado"
/>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `input` | Valor alterado | `{ value: string }` |
| `blur` | Campo perdeu foco | `{ value: string }` |
| `focus` | Campo ganhou foco | `{ value: string }` |
| `change` | Valor confirmado | `{ value: string }` |

## Performance

- **Debounced Input**: Para buscas e validações
- **Lazy Validation**: Validação só quando necessário
- **Input Masking**: Processamento eficiente
- **Memory Management**: Limpeza de event listeners

## Testes

**Cobertura**: 18 testes automatizados
- Renderização e propriedades
- Validação e estados
- Interações do usuário
- Acessibilidade
- Máscaras e formatação

## Relacionados

- [Form Group](form-group.md) - Para labels e erros
- [Textarea](textarea.md) - Para texto longo
- [Select](select.md) - Para seleções
- [Checkbox](checkbox.md) - Para booleanos
- [Radio](radio.md) - Para opções únicas