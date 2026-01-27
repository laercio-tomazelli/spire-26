# Select Component

O componente Select fornece dropdowns customizáveis com busca, múltipla seleção e opções avançadas.

## Uso Básico

```blade
<x-spire::select>
    <option value="1">Opção 1</option>
    <option value="2">Opção 2</option>
    <option value="3">Opção 3</option>
</x-spire::select>
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `options` | array | `[]` | Array de opções |
| `value` | string\|array | - | Valor selecionado |
| `multiple` | boolean | `false` | Seleção múltipla |
| `searchable` | boolean | `false` | Busca habilitada |
| `placeholder` | string | `"Selecione..."` | Placeholder |
| `disabled` | boolean | `false` | Campo desabilitado |
| `required` | boolean | `false` | Campo obrigatório |
| `size` | string | `"md"` | Tamanho: `sm`, `md`, `lg` |
| `variant` | string | `"default"` | Variante: `default`, `error`, `success` |
| `maxHeight` | string | `"200px"` | Altura máxima do dropdown |
| `clearable` | boolean | `false` | Botão para limpar seleção |
| `loading` | boolean | `false` | Estado de carregamento |

## Estrutura de Opções

### Array Simples

```php
$options = [
    'opcao1' => 'Opção 1',
    'opcao2' => 'Opção 2',
    'opcao3' => 'Opção 3'
];
```

### Array de Objetos

```php
$options = [
    ['value' => '1', 'label' => 'Opção 1', 'group' => 'Grupo A'],
    ['value' => '2', 'label' => 'Opção 2', 'group' => 'Grupo A'],
    ['value' => '3', 'label' => 'Opção 3', 'group' => 'Grupo B']
];
```

## Exemplos

### Select Básico

```blade
<x-spire::select name="category">
    <option value="">Selecione uma categoria</option>
    <option value="electronics">Eletrônicos</option>
    <option value="books">Livros</option>
    <option value="clothing">Roupas</option>
</x-spire::select>
```

### Com Array de Opções

```blade
<x-spire::select :options="$categories" name="category" />
```

### Select Pesquisável

```blade
<x-spire::select
    :options="$countries"
    searchable
    placeholder="Selecione um país..."
/>
```

### Seleção Múltipla

```blade
<x-spire::select
    :options="$skills"
    multiple
    placeholder="Selecione suas habilidades..."
/>
```

### Com Grupos

```blade
<x-spire::select :options="$groupedOptions">
    <x-slot:option-group="{ group }">
        <strong>{{ group }}</strong>
    </x-slot:option-group>
</x-spire::select>
```

### Com Ícones

```blade
<x-spire::select :options="$socialMedia">
    <x-slot:option="{ option }">
        <div class="flex items-center space-x-2">
            <x-spire::icon :name="option.icon" class="w-4 h-4" />
            <span>{{ option.label }}</span>
        </div>
    </x-slot:option>
</x-spire::select>
```

## Estados e Variantes

### Estados

```blade
<!-- Normal -->
<x-spire::select :options="$options" />

<!-- Sucesso -->
<x-spire::select :options="$options" variant="success" />

<!-- Erro -->
<x-spire::select :options="$options" variant="error" />

<!-- Desabilitado -->
<x-spire::select :options="$options" disabled />

<!-- Carregando -->
<x-spire::select :options="$options" loading />
```

### Tamanhos

```blade
<x-spire::select :options="$options" size="sm" />
<x-spire::select :options="$options" size="md" />
<x-spire::select :options="$options" size="lg" />
```

### Com Botão de Limpar

```blade
<x-spire::select :options="$options" clearable />
```

## Integração com Formulários

### Laravel Collective

```blade
{{ Form::spireSelect('country', $countries, null, ['searchable' => true]) }}
{{ Form::spireSelect('skills[]', $skills, $selectedSkills, ['multiple' => true]) }}
```

### Com Labels e Erros

```blade
<x-spire::form-group label="País" :error="$errors->first('country')">
    <x-spire::select
        name="country"
        :options="$countries"
        :value="old('country')"
        searchable
        :variant="$errors->has('country') ? 'error' : 'default'"
    />
</x-spire::form-group>
```

### Validação

```blade
<x-spire::form-group label="Estado" :error="$errors->first('state')">
    <x-spire::select
        name="state"
        :options="$states"
        required
        :variant="$errors->has('state') ? 'error' : 'default'"
    />
</x-spire::form-group>
```

## Integração com Alpine.js

### Busca Dinâmica (API)

```blade
<div x-data="dynamicSelect">
    <x-spire::select
        x-model="selectedCountry"
        :options="countries"
        searchable
        placeholder="Selecione um país..."
        @change="loadStates"
    />

    <x-spire::select
        x-show="states.length > 0"
        x-model="selectedState"
        :options="states"
        placeholder="Selecione um estado..."
        :disabled="!selectedCountry"
    />
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dynamicSelect', () => ({
        countries: @json($countries),
        states: [],
        selectedCountry: '',
        selectedState: '',

        async loadStates() {
            if (!this.selectedCountry) {
                this.states = [];
                return;
            }

            const response = await fetch(`/api/states/${this.selectedCountry}`);
            this.states = await response.json();
        }
    }));
});
</script>
```

### Seleção Múltipla com Tags

```blade
<div x-data="multiSelect">
    <x-spire::select
        x-model="selectedTags"
        :options="$tags"
        multiple
        searchable
        placeholder="Selecione tags..."
    />

    <div x-show="selectedTags.length > 0" class="mt-2 flex flex-wrap gap-1">
        <template x-for="tag in selectedTags" :key="tag">
            <x-spire::badge variant="secondary" class="flex items-center gap-1">
                <span x-text="getTagLabel(tag)"></span>
                <button @click="removeTag(tag)" class="ml-1 text-xs">×</button>
            </x-spire::badge>
        </template>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('multiSelect', () => ({
        selectedTags: [],

        getTagLabel(value) {
            const tag = @json($tags).find(t => t.value === value);
            return tag ? tag.label : value;
        },

        removeTag(tag) {
            this.selectedTags = this.selectedTags.filter(t => t !== tag);
        }
    }));
});
</script>
```

### Filtro Dependente

```blade
<div x-data="dependentSelect">
    <x-spire::select
        x-model="category"
        :options="$categories"
        placeholder="Selecione uma categoria..."
    />

    <x-spire::select
        x-show="category"
        x-model="subcategory"
        :options="getSubcategories()"
        placeholder="Selecione uma subcategoria..."
    />
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dependentSelect', () => ({
        category: '',
        subcategory: '',
        allSubcategories: @json($subcategories),

        getSubcategories() {
            return this.allSubcategories.filter(sub => sub.category_id === this.category);
        }
    }));
});
</script>
```

## Customização Avançada

### Templates Customizados

```blade
<x-spire::select :options="$users">
    <x-slot:option="{ option }">
        <div class="flex items-center space-x-3">
            <img :src="option.avatar" class="w-8 h-8 rounded-full" />
            <div>
                <div class="font-medium">{{ option.name }}</div>
                <div class="text-sm text-gray-500">{{ option.email }}</div>
            </div>
        </div>
    </x-slot:option>

    <x-slot:selected-option="{ option }">
        <div class="flex items-center space-x-2">
            <img :src="option.avatar" class="w-6 h-6 rounded-full" />
            <span>{{ option.name }}</span>
        </div>
    </x-slot:selected-option>
</x-spire::select>
```

### Grupos Customizados

```blade
<x-spire::select :options="$groupedProducts">
    <x-slot:option-group="{ group }">
        <div class="flex items-center justify-between">
            <span class="font-semibold">{{ group.name }}</span>
            <span class="text-sm text-gray-500">{{ group.count }} produtos</span>
        </div>
    </x-slot:option-group>
</x-spire::select>
```

## Acessibilidade

- **Labels**: Sempre use com `<x-spire::form-group>`
- **Navegação**: Arrow keys, Enter, Escape
- **Leitores de Tela**: Anúncios adequados para opções
- **Estados**: `aria-expanded`, `aria-selected`
- **Busca**: Filtragem acessível
- **Múltipla**: Seleção clara

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/select-custom.css */
.spire-select {
    @apply relative;
}

.spire-select__trigger {
    @apply border border-gray-300 rounded-md px-3 py-2 bg-white cursor-pointer;
}

.spire-select__dropdown {
    @apply absolute z-50 mt-1 bg-white border border-gray-300 rounded-md shadow-lg;
}

.spire-select__option {
    @apply px-3 py-2 cursor-pointer hover:bg-gray-100;
}

.spire-select__option--selected {
    @apply bg-blue-50 text-blue-700;
}
```

### Select Customizado

```blade
<x-spire::select :options="$options" class="custom-select" />
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `change` | Seleção alterada | `{ value: string\|array }` |
| `open` | Dropdown aberto | - |
| `close` | Dropdown fechado | - |
| `search` | Busca realizada | `{ query: string }` |
| `clear` | Seleção limpa | - |

## Performance

- **Virtualização**: Para listas grandes (>1000 opções)
- **Debounced Search**: Busca otimizada
- **Lazy Loading**: Carregamento sob demanda
- **Memoização**: Opções cacheadas

## Testes

**Cobertura**: 22 testes automatizados
- Renderização e propriedades
- Interações (seleção, busca, múltipla)
- Estados e validação
- Acessibilidade
- Performance com listas grandes

## Relacionados

- [Input](input.md) - Para entrada de texto
- [Checkbox](checkbox.md) - Para múltiplas seleções booleanas
- [Radio](radio.md) - Para seleção única obrigatória
- [Form Group](form-group.md) - Para labels e validação
- [Autocomplete](autocomplete.md) - Para busca avançada