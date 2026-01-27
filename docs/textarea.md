# Textarea Component

O componente Textarea fornece campos de texto multilinha com auto-resize, validação e formatação.

## Uso Básico

```blade
<x-spire::textarea placeholder="Digite sua mensagem..." />
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `placeholder` | string | - | Placeholder do campo |
| `value` | string | - | Valor do campo |
| `rows` | number | `3` | Número de linhas visíveis |
| `maxlength` | number | - | Máximo de caracteres |
| `minlength` | number | - | Mínimo de caracteres |
| `disabled` | boolean | `false` | Campo desabilitado |
| `readonly` | boolean | `false` | Campo somente leitura |
| `required` | boolean | `false` | Campo obrigatório |
| `autofocus` | boolean | `false` | Foco automático |
| `resize` | string | `"vertical"` | Redimensionamento: `none`, `vertical`, `horizontal`, `both` |
| `autoResize` | boolean | `false` | Auto-resize baseado no conteúdo |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |
| `variant` | string | `"default"` | Variante: `default`, `error`, `success` |

## Exemplos

### Textarea Básico

```blade
<x-spire::textarea placeholder="Digite sua mensagem aqui..." />
```

### Com Número de Linhas

```blade
<x-spire::textarea rows="5" placeholder="Descrição detalhada..." />
```

### Auto-resize

```blade
<x-spire::textarea auto-resize placeholder="Conteúdo que cresce automaticamente..." />
```

### Tamanhos

```blade
<x-spire::textarea size="sm" placeholder="Textarea pequeno" />
<x-spire::textarea size="md" placeholder="Textarea médio" />
<x-spire::textarea size="lg" placeholder="Textarea grande" />
```

### Estados

```blade
<!-- Normal -->
<x-spire::textarea placeholder="Campo normal" />

<!-- Sucesso -->
<x-spire::textarea variant="success" placeholder="Campo válido" />

<!-- Erro -->
<x-spire::textarea variant="error" placeholder="Campo com erro" />

<!-- Desabilitado -->
<x-spire::textarea disabled placeholder="Campo desabilitado" />

<!-- Somente leitura -->
<x-spire::textarea readonly value="Campo somente leitura" />
```

### Com Validação

```blade
<!-- Campo obrigatório -->
<x-spire::textarea required placeholder="Campo obrigatório" />

<!-- Comprimento mínimo -->
<x-spire::textarea minlength="10" placeholder="Mínimo 10 caracteres" />

<!-- Comprimento máximo -->
<x-spire::textarea maxlength="500" placeholder="Máximo 500 caracteres" />
```

### Sem Redimensionamento

```blade
<x-spire::textarea resize="none" placeholder="Sem redimensionamento" />
```

## Integração com Formulários

### Laravel Collective

```blade
{{ Form::spireTextarea('description', 'Descrição', ['rows' => 5, 'required' => true]) }}
{{ Form::spireTextarea('comments', 'Comentários', ['maxlength' => 1000]) }}
```

### Com Labels e Contador

```blade
<x-spire::form-group label="Descrição" description="Descreva detalhadamente">
    <x-spire::textarea
        name="description"
        rows="4"
        maxlength="500"
        required
        auto-resize
    />
    <div class="text-sm text-gray-500 mt-1">
        <span x-text="description.length"></span>/500 caracteres
    </div>
</x-spire::form-group>
```

### Com Erros

```blade
<x-spire::form-group label="Comentários" :error="$errors->first('comments')">
    <x-spire::textarea
        name="comments"
        :value="old('comments')"
        rows="3"
        :variant="$errors->has('comments') ? 'error' : 'default'"
    />
</x-spire::form-group>
```

## Integração com Alpine.js

### Contador de Caracteres

```blade
<div x-data="textareaCounter">
    <x-spire::form-group label="Descrição">
        <x-spire::textarea
            x-model="description"
            rows="4"
            maxlength="500"
            auto-resize
            placeholder="Digite sua descrição..."
        />
    </x-spire::form-group>

    <div class="flex justify-between text-sm text-gray-500 mt-1">
        <span x-text="description.length + ' caracteres'"></span>
        <span x-text="'Restam ' + (500 - description.length) + ' caracteres'"></span>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
        <div
            class="bg-blue-600 h-2 rounded-full transition-all duration-300"
            :style="`width: ${(description.length / 500) * 100}%`"
        ></div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('textareaCounter', () => ({
        description: ''
    }));
});
</script>
```

### Validação em Tempo Real

```blade
<div x-data="textareaValidation">
    <x-spire::form-group label="Comentários" :error="errorMessage">
        <x-spire::textarea
            x-model="comments"
            @blur="validateComments"
            rows="3"
            minlength="10"
            :variant="errorMessage ? 'error' : (isValid ? 'success' : 'default')"
            placeholder="Digite pelo menos 10 caracteres..."
        />
    </x-spire::form-group>

    <div x-show="isValid" class="text-green-600 text-sm mt-1">
        ✓ Comentário válido
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('textareaValidation', () => ({
        comments: '',
        errorMessage: '',
        isValid: false,

        validateComments() {
            if (this.comments.length < 10) {
                this.errorMessage = 'Comentário deve ter pelo menos 10 caracteres';
                this.isValid = false;
            } else {
                this.errorMessage = '';
                this.isValid = true;
            }
        }
    }));
});
</script>
```

### Auto-resize com Máximo

```blade
<div x-data="autoResizeTextarea">
    <x-spire::textarea
        x-model="content"
        :rows="Math.min(Math.max(content.split('\n').length, 3), 10)"
        auto-resize
        placeholder="Conteúdo que cresce até 10 linhas..."
    />

    <div class="text-sm text-gray-500 mt-1">
        Linhas: <span x-text="content.split('\n').length"></span>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('autoResizeTextarea', () => ({
        content: ''
    }));
});
</script>
```

### Editor Simples

```blade
<div x-data="simpleEditor">
    <div class="border rounded-md">
        <div class="flex border-b bg-gray-50 px-3 py-2">
            <x-spire::button
                size="sm"
                variant="ghost"
                @click="insertText('**', '**')"
            >
                <strong>B</strong>
            </x-spire::button>
            <x-spire::button
                size="sm"
                variant="ghost"
                @click="insertText('*', '*')"
            >
                <em>I</em>
            </x-spire::button>
            <x-spire::button
                size="sm"
                variant="ghost"
                @click="insertText('`', '`')"
            >
                <code>`</code>
            </x-spire::button>
        </div>

        <x-spire::textarea
            x-model="content"
            rows="6"
            auto-resize
            class="border-0 rounded-none"
            placeholder="Digite seu texto..."
        />
    </div>

    <div class="mt-4 p-4 bg-gray-50 rounded">
        <h4 class="font-semibold mb-2">Preview:</h4>
        <div x-html="parseMarkdown(content)" class="prose prose-sm"></div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('simpleEditor', () => ({
        content: '',

        insertText(before, after) {
            const textarea = this.$refs.textarea;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = this.content.substring(start, end);
            const newText = before + selectedText + after;

            this.content = this.content.substring(0, start) + newText + this.content.substring(end);
        },

        parseMarkdown(text) {
            // Simple markdown parser
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/`(.*?)`/g, '<code>$1</code>')
                .replace(/\n/g, '<br>');
        }
    }));
});
</script>
```

## Acessibilidade

- **Labels**: Sempre use com `<x-spire::form-group>`
- **Descrições**: Use `aria-describedby` para instruções
- **Contadores**: Anuncie contadores com `aria-live`
- **Estados**: `aria-disabled`, `aria-readonly`
- **Navegação**: Tab order correto
- **Leitores de Tela**: Anúncios adequados

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/textarea-custom.css */
.spire-textarea {
    @apply border border-gray-300 rounded-md px-3 py-2 resize-vertical;
}

.spire-textarea:focus {
    @apply ring-2 ring-blue-500 border-blue-500;
}

.spire-textarea--error {
    @apply border-red-500 focus:ring-red-500;
}

.spire-textarea--success {
    @apply border-green-500 focus:ring-green-500;
}

.spire-textarea--disabled {
    @apply bg-gray-100 cursor-not-allowed resize-none;
}
```

### Textarea Customizado

```blade
<x-spire::textarea
    class="custom-textarea"
    placeholder="Textarea customizado"
/>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `input` | Conteúdo alterado | `{ value: string }` |
| `blur` | Campo perdeu foco | `{ value: string }` |
| `focus` | Campo ganhou foco | `{ value: string }` |
| `change` | Conteúdo confirmado | `{ value: string }` |
| `resize` | Campo redimensionado | `{ height: number }` |

## Performance

- **Auto-resize**: Cálculo eficiente de altura
- **Debounced Events**: Eventos otimizados
- **Memory Management**: Limpeza adequada
- **Large Content**: Tratamento de conteúdo grande

## Testes

**Cobertura**: 15 testes automatizados
- Renderização e propriedades
- Auto-resize e redimensionamento
- Validação e estados
- Interações do usuário
- Acessibilidade

## Relacionados

- [Input](input.md) - Para entrada de texto simples
- [Form Group](form-group.md) - Para labels e validação
- [Rich Text Editor](rich-text-editor.md) - Para edição avançada
- [Markdown Editor](markdown-editor.md) - Para conteúdo formatado