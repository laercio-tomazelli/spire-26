# Form Group Component

O componente Form Group fornece agrupamento estruturado para campos de formulário com labels, erros e descrições.

## Uso Básico

```blade
<x-spire::form-group label="Nome">
    <x-spire::input name="name" />
</x-spire::form-group>
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `label` | string | - | Texto do label |
| `description` | string | - | Descrição adicional |
| `error` | string | - | Mensagem de erro |
| `required` | boolean | `false` | Campo obrigatório |
| `disabled` | boolean | `false` | Grupo desabilitado |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |
| `layout` | string | `"vertical"` | Layout: `vertical`, `horizontal` |
| `labelWidth` | string | - | Largura do label (horizontal) |

## Exemplos

### Form Group Básico

```blade
<x-spire::form-group label="Nome Completo">
    <x-spire::input name="name" placeholder="Digite seu nome" />
</x-spire::form-group>

<x-spire::form-group label="Email">
    <x-spire::input type="email" name="email" placeholder="seu@email.com" />
</x-spire::form-group>
```

### Com Descrição

```blade
<x-spire::form-group
    label="Senha"
    description="Use pelo menos 8 caracteres com letras e números"
>
    <x-spire::input type="password" name="password" />
</x-spire::form-group>
```

### Com Erro

```blade
<x-spire::form-group
    label="Email"
    error="Este email já está cadastrado"
>
    <x-spire::input type="email" name="email" value="teste@teste.com" />
</x-spire::form-group>
```

### Campo Obrigatório

```blade
<x-spire::form-group label="Nome" required>
    <x-spire::input name="name" required />
</x-spire::form-group>
```

### Layout Horizontal

```blade
<x-spire::form-group label="Nome" layout="horizontal" label-width="120px">
    <x-spire::input name="name" />
</x-spire::form-group>

<x-spire::form-group label="Email" layout="horizontal" label-width="120px">
    <x-spire::input type="email" name="email" />
</x-spire::form-group>
```

### Tamanhos

```blade
<x-spire::form-group label="Campo Pequeno" size="sm">
    <x-spire::input size="sm" />
</x-spire::form-group>

<x-spire::form-group label="Campo Médio" size="md">
    <x-spire::input size="md" />
</x-spire::form-group>

<x-spire::form-group label="Campo Grande" size="lg">
    <x-spire::input size="lg" />
</x-spire::form-group>
```

## Integração com Laravel

### Com Validação

```blade
<x-spire::form-group
    label="Nome"
    :error="$errors->first('name')"
    :required="true"
>
    <x-spire::input
        name="name"
        :value="old('name')"
        :variant="$errors->has('name') ? 'error' : 'default'"
        required
    />
</x-spire::form-group>

<x-spire::form-group
    label="Email"
    :error="$errors->first('email')"
    description="Será usado para login"
>
    <x-spire::input
        type="email"
        name="email"
        :value="old('email')"
        :variant="$errors->has('email') ? 'error' : 'default'"
        required
    />
</x-spire::form-group>
```

### Formulário Completo

```blade
<form method="POST" action="/register">
    @csrf

    <div class="space-y-6">
        <x-spire::form-group
            label="Nome Completo"
            :error="$errors->first('name')"
            required
        >
            <x-spire::input
                name="name"
                :value="old('name')"
                :variant="$errors->has('name') ? 'error' : 'default'"
                placeholder="Digite seu nome completo"
            />
        </x-spire::form-group>

        <x-spire::form-group
            label="Email"
            :error="$errors->first('email')"
            description="Será usado para confirmação da conta"
            required
        >
            <x-spire::input
                type="email"
                name="email"
                :value="old('email')"
                :variant="$errors->has('email') ? 'error' : 'default'"
                placeholder="seu@email.com"
            />
        </x-spire::form-group>

        <x-spire::form-group
            label="Senha"
            :error="$errors->first('password')"
            description="Mínimo 8 caracteres"
            required
        >
            <x-spire::input
                type="password"
                name="password"
                :variant="$errors->has('password') ? 'error' : 'default'"
            />
        </x-spire::form-group>

        <x-spire::form-group
            label="Confirmar Senha"
            :error="$errors->first('password_confirmation')"
            required
        >
            <x-spire::input
                type="password"
                name="password_confirmation"
                :variant="$errors->has('password_confirmation') ? 'error' : 'default'"
            />
        </x-spire::form-group>

        <x-spire::form-group>
            <x-spire::checkbox
                name="terms"
                label="Aceito os termos de uso"
                :variant="$errors->has('terms') ? 'error' : 'default'"
            />
            <div x-show="$errors->has('terms')" class="text-red-600 text-sm mt-1">
                {{ $errors->first('terms') }}
            </div>
        </x-spire::form-group>
    </div>

    <div class="mt-8">
        <x-spire::button type="submit">Criar Conta</x-spire::button>
    </div>
</form>
```

## Integração com Alpine.js

### Validação em Tempo Real

```blade
<div x-data="formValidation">
    <x-spire::form-group
        label="Nome"
        :error="nameError"
        required
    >
        <x-spire::input
            x-model="name"
            @blur="validateName"
            :variant="nameError ? 'error' : (nameValid ? 'success' : 'default')"
        />
    </x-spire::form-group>

    <x-spire::form-group
        label="Email"
        :error="emailError"
        required
    >
        <x-spire::input
            type="email"
            x-model="email"
            @blur="validateEmail"
            :variant="emailError ? 'error' : (emailValid ? 'success' : 'default')"
        />
    </x-spire::form-group>

    <x-spire::form-group
        label="Idade"
        :error="ageError"
        description="Entre 18 e 100 anos"
    >
        <x-spire::input
            type="number"
            x-model="age"
            @blur="validateAge"
            min="18"
            max="100"
            :variant="ageError ? 'error' : (ageValid ? 'success' : 'default')"
        />
    </x-spire::form-group>

    <x-spire::button @click="submitForm" :disabled="!isValid" class="mt-4">
        Enviar
    </x-spire::button>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('formValidation', () => ({
        name: '',
        email: '',
        age: '',

        nameError: '',
        emailError: '',
        ageError: '',

        nameValid: false,
        emailValid: false,
        ageValid: false,

        validateName() {
            if (!this.name.trim()) {
                this.nameError = 'Nome é obrigatório';
                this.nameValid = false;
            } else if (this.name.trim().length < 2) {
                this.nameError = 'Nome deve ter pelo menos 2 caracteres';
                this.nameValid = false;
            } else {
                this.nameError = '';
                this.nameValid = true;
            }
        },

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
        },

        validateAge() {
            const age = parseInt(this.age);
            if (!this.age) {
                this.ageError = 'Idade é obrigatória';
                this.ageValid = false;
            } else if (age < 18 || age > 100) {
                this.ageError = 'Idade deve estar entre 18 e 100 anos';
                this.ageValid = false;
            } else {
                this.ageError = '';
                this.ageValid = true;
            }
        },

        get isValid() {
            return this.nameValid && this.emailValid && (!this.age || this.ageValid);
        },

        submitForm() {
            if (this.isValid) {
                console.log('Formulário válido:', {
                    name: this.name,
                    email: this.email,
                    age: this.age
                });
            }
        }
    }));
});
</script>
```

### Formulário Dinâmico

```blade
<div x-data="dynamicForm">
    <x-spire::form-group
        label="Tipo de Usuário"
        description="Selecione o tipo para ver campos específicos"
    >
        <x-spire::select
            x-model="userType"
            :options="userTypes"
            placeholder="Selecione um tipo"
        />
    </x-spire::form-group>

    <template x-if="userType === 'individual'">
        <x-spire::form-group label="CPF" required>
            <x-spire::input
                x-model="cpf"
                mask="999.999.999-99"
                placeholder="000.000.000-00"
            />
        </x-spire::form-group>

        <x-spire::form-group label="Data de Nascimento" required>
            <x-spire::input
                x-model="birthDate"
                mask="99/99/9999"
                placeholder="DD/MM/AAAA"
            />
        </x-spire::form-group>
    </template>

    <template x-if="userType === 'company'">
        <x-spire::form-group label="CNPJ" required>
            <x-spire::input
                x-model="cnpj"
                mask="99.999.999/9999-99"
                placeholder="00.000.000/0000-00"
            />
        </x-spire::form-group>

        <x-spire::form-group label="Razão Social" required>
            <x-spire::input
                x-model="companyName"
                placeholder="Nome da empresa"
            />
        </x-spire::form-group>
    </template>

    <x-spire::button
        x-show="userType"
        @click="submitForm"
        class="mt-4"
    >
        Cadastrar
    </x-spire::button>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dynamicForm', () => ({
        userType: '',
        userTypes: [
            { value: 'individual', label: 'Pessoa Física' },
            { value: 'company', label: 'Pessoa Jurídica' }
        ],

        // Campos pessoa física
        cpf: '',
        birthDate: '',

        // Campos pessoa jurídica
        cnpj: '',
        companyName: '',

        submitForm() {
            const data = {
                userType: this.userType,
                ...(this.userType === 'individual' && {
                    cpf: this.cpf,
                    birthDate: this.birthDate
                }),
                ...(this.userType === 'company' && {
                    cnpj: this.cnpj,
                    companyName: this.companyName
                })
            };

            console.log('Dados do formulário:', data);
        }
    }));
});
</script>
```

### Grupo de Campos

```blade
<div x-data="fieldGroup">
    <fieldset class="border rounded-lg p-4">
        <legend class="text-lg font-semibold px-2">Endereço</legend>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-spire::form-group label="CEP" required>
                <x-spire::input
                    x-model="cep"
                    mask="99999-999"
                    placeholder="00000-000"
                    @blur="searchCep"
                />
            </x-spire::form-group>

            <x-spire::form-group label="Rua" required>
                <x-spire::input x-model="street" placeholder="Nome da rua" />
            </x-spire::form-group>

            <x-spire::form-group label="Número" required>
                <x-spire::input x-model="number" placeholder="123" />
            </x-spire::form-group>

            <x-spire::form-group label="Complemento">
                <x-spire::input x-model="complement" placeholder="Apto, bloco, etc." />
            </x-spire::form-group>

            <x-spire::form-group label="Bairro" required>
                <x-spire::input x-model="neighborhood" placeholder="Nome do bairro" />
            </x-spire::form-group>

            <x-spire::form-group label="Cidade" required>
                <x-spire::input x-model="city" placeholder="Nome da cidade" />
            </x-spire::form-group>

            <x-spire::form-group label="Estado" required>
                <x-spire::select
                    x-model="state"
                    :options="states"
                    placeholder="Selecione o estado"
                />
            </x-spire::form-group>
        </div>
    </fieldset>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fieldGroup', () => ({
        cep: '',
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',

        states: @json($states), // Lista de estados

        async searchCep() {
            if (this.cep.length === 9) { // 00000-000
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${this.cep.replace('-', '')}/json/`);
                    const data = await response.json();

                    if (!data.erro) {
                        this.street = data.logradouro;
                        this.neighborhood = data.bairro;
                        this.city = data.localidade;
                        this.state = data.uf;
                    }
                } catch (error) {
                    console.error('Erro ao buscar CEP:', error);
                }
            }
        }
    }));
});
</script>
```

## Acessibilidade

- **Labels**: Sempre associados aos campos
- **Erros**: Anunciados por leitores de tela
- **Descrições**: `aria-describedby` para instruções
- **Estados**: Indicação clara de erros
- **Navegação**: Tab order lógico

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/form-group-custom.css */
.spire-form-group {
    @apply mb-4;
}

.spire-form-group__label {
    @apply block text-sm font-medium text-gray-700 mb-1;
}

.spire-form-group__label--required::after {
    content: ' *';
    @apply text-red-500;
}

.spire-form-group__description {
    @apply text-sm text-gray-500 mb-2;
}

.spire-form-group__error {
    @apply text-sm text-red-600 mt-1;
}

.spire-form-group--horizontal {
    @apply flex items-center;
}

.spire-form-group--horizontal .spire-form-group__label {
    @apply mr-4 mb-0 flex-shrink-0;
}
```

### Form Group Customizado

```blade
<x-spire::form-group class="custom-form-group" label="Campo Customizado">
    <x-spire::input />
</x-spire::form-group>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `focus` | Campo ganhou foco | - |
| `blur` | Campo perdeu foco | - |
| `input` | Valor alterado | `{ value: any }` |
| `change` | Valor confirmado | `{ value: any }` |

## Performance

- **Lazy Validation**: Só valida quando necessário
- **Debounced Events**: Eventos otimizados
- **Memory Management**: Limpeza adequada
- **Re-renders**: Minimização de re-renders

## Testes

**Cobertura**: 12 testes automatizados
- Renderização e layouts
- Estados de erro e sucesso
- Validação e acessibilidade
- Interações do usuário
- Performance

## Relacionados

- [Input](input.md) - Campos de entrada
- [Select](select.md) - Seleções dropdown
- [Textarea](textarea.md) - Campos de texto longo
- [Checkbox](checkbox.md) - Caixas de seleção
- [Radio](radio.md) - Botões de rádio