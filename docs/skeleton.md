# Skeleton Component

O componente Skeleton fornece placeholders animados para indicar carregamento de conteúdo.

## Uso Básico

```blade
<x-spire::skeleton />
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `width` | string | - | Largura do skeleton |
| `height` | string | - | Altura do skeleton |
| `variant` | string | `"text"` | Variante: `text`, `rectangular`, `circular` |
| `animation` | string | `"pulse"` | Animação: `pulse`, `wave`, `none` |
| `lines` | number | `1` | Número de linhas (para text) |

## Exemplos

### Skeleton de Texto

```blade
<x-spire::skeleton variant="text" />
<x-spire::skeleton variant="text" width="200px" />
<x-spire::skeleton variant="text" width="50%" />
```

### Múltiplas Linhas

```blade
<x-spire::skeleton variant="text" lines="3" />
```

### Skeleton Retangular

```blade
<x-spire::skeleton variant="rectangular" width="200px" height="100px" />
<x-spire::skeleton variant="rectangular" width="100%" height="200px" />
```

### Skeleton Circular

```blade
<x-spire::skeleton variant="circular" width="40px" height="40px" />
<x-spire::skeleton variant="circular" width="60px" height="60px" />
```

### Animações

```blade
<x-spire::skeleton animation="pulse" />
<x-spire::skeleton animation="wave" />
<x-spire::skeleton animation="none" />
```

## Layouts Completos

### Card de Produto

```blade
<div class="border rounded-lg p-4">
    <x-spire::skeleton variant="rectangular" width="100%" height="200px" class="mb-4" />
    <x-spire::skeleton variant="text" width="80%" class="mb-2" />
    <x-spire::skeleton variant="text" width="60%" class="mb-4" />
    <div class="flex justify-between items-center">
        <x-spire::skeleton variant="text" width="40%" />
        <x-spire::skeleton variant="rectangular" width="80px" height="32px" />
    </div>
</div>
```

### Lista de Usuários

```blade
<div class="space-y-4">
    <template x-for="i in 5" :key="i">
        <div class="flex items-center space-x-4">
            <x-spire::skeleton variant="circular" width="48px" height="48px" />
            <div class="flex-1">
                <x-spire::skeleton variant="text" width="60%" class="mb-1" />
                <x-spire::skeleton variant="text" width="40%" />
            </div>
        </div>
    </template>
</div>
```

### Tabela

```blade
<div class="border rounded-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gray-50 px-4 py-3 border-b">
        <div class="grid grid-cols-4 gap-4">
            <x-spire::skeleton variant="text" width="80%" />
            <x-spire::skeleton variant="text" width="60%" />
            <x-spire::skeleton variant="text" width="70%" />
            <x-spire::skeleton variant="text" width="50%" />
        </div>
    </div>

    <!-- Rows -->
    <template x-for="i in 5" :key="i">
        <div class="px-4 py-3 border-b last:border-b-0">
            <div class="grid grid-cols-4 gap-4">
                <x-spire::skeleton variant="text" width="75%" />
                <x-spire::skeleton variant="text" width="55%" />
                <x-spire::skeleton variant="text" width="65%" />
                <x-spire::skeleton variant="rectangular" width="60px" height="24px" />
            </div>
        </div>
    </template>
</div>
```

### Formulário

```blade
<div class="space-y-6">
    <x-spire::form-group label="Nome">
        <x-spire::skeleton variant="rectangular" width="100%" height="40px" />
    </x-spire::form-group>

    <x-spire::form-group label="Email">
        <x-spire::skeleton variant="rectangular" width="100%" height="40px" />
    </x-spire::form-group>

    <x-spire::form-group label="Mensagem">
        <x-spire::skeleton variant="rectangular" width="100%" height="100px" />
    </x-spire::form-group>

    <x-spire::skeleton variant="rectangular" width="120px" height="40px" />
</div>
```

## Integração com Alpine.js

### Loading State

```blade
<div x-data="dataLoader">
    <template x-if="loading">
        <!-- Skeleton State -->
        <div class="space-y-4">
            <x-spire::skeleton variant="text" lines="2" />
            <x-spire::skeleton variant="rectangular" width="100%" height="300px" />
            <div class="flex space-x-4">
                <x-spire::skeleton variant="circular" width="40px" height="40px" />
                <div class="flex-1">
                    <x-spire::skeleton variant="text" width="60%" />
                    <x-spire::skeleton variant="text" width="40%" />
                </div>
            </div>
        </div>
    </template>

    <template x-if="!loading">
        <!-- Real Content -->
        <div class="space-y-4">
            <div>
                <h2 class="text-xl font-bold" x-text="post.title"></h2>
                <p class="text-gray-600" x-text="post.excerpt"></p>
            </div>
            <img :src="post.image" class="w-full h-64 object-cover rounded" />
            <div class="flex items-center space-x-4">
                <img :src="post.author.avatar" class="w-10 h-10 rounded-full" />
                <div>
                    <p class="font-medium" x-text="post.author.name"></p>
                    <p class="text-sm text-gray-500" x-text="post.date"></p>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dataLoader', () => ({
        loading: true,
        post: {},

        async init() {
            // Simulate API call
            setTimeout(() => {
                this.post = {
                    title: 'Título do Post',
                    excerpt: 'Este é um exemplo de post carregado...',
                    image: '/images/post.jpg',
                    author: {
                        name: 'João Silva',
                        avatar: '/images/avatar.jpg'
                    },
                    date: '2 horas atrás'
                };
                this.loading = false;
            }, 2000);
        }
    }));
});
</script>
```

### Progressive Loading

```blade
<div x-data="progressiveLoader">
    <div class="space-y-4">
        <!-- Sempre visível -->
        <div class="flex items-center space-x-4">
            <x-spire::skeleton
                x-show="loading"
                variant="circular"
                width="48px"
                height="48px"
            />
            <template x-if="!loading">
                <img :src="user.avatar" class="w-12 h-12 rounded-full" />
            </template>

            <div class="flex-1">
                <x-spire::skeleton
                    x-show="loading"
                    variant="text"
                    width="40%"
                    class="mb-1"
                />
                <template x-if="!loading">
                    <h3 class="font-semibold" x-text="user.name"></h3>
                </template>

                <x-spire::skeleton
                    x-show="loading"
                    variant="text"
                    width="60%"
                />
                <template x-if="!loading">
                    <p class="text-gray-600" x-text="user.bio"></p>
                </template>
            </div>
        </div>

        <!-- Aparece depois -->
        <div x-show="!loading" x-transition class="grid grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold" x-text="user.posts"></div>
                <div class="text-gray-600">Posts</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold" x-text="user.followers"></div>
                <div class="text-gray-600">Seguidores</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold" x-text="user.following"></div>
                <div class="text-gray-600">Seguindo</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('progressiveLoader', () => ({
        loading: true,
        user: {},

        async init() {
            // Load basic info first
            setTimeout(() => {
                this.user = {
                    avatar: '/images/avatar.jpg',
                    name: 'João Silva',
                    bio: 'Desenvolvedor web apaixonado por tecnologia'
                };
            }, 1000);

            // Load stats later
            setTimeout(() => {
                this.user = {
                    ...this.user,
                    posts: 42,
                    followers: 1234,
                    following: 567
                };
                this.loading = false;
            }, 2000);
        }
    }));
});
</script>
```

### Infinite Scroll com Skeleton

```blade
<div x-data="infiniteScroll">
    <div class="space-y-4">
        <!-- Items carregados -->
        <template x-for="item in items" :key="item.id">
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold" x-text="item.title"></h3>
                <p class="text-gray-600" x-text="item.description"></p>
            </div>
        </template>

        <!-- Skeletons de loading -->
        <template x-if="loading">
            <template x-for="i in 3" :key="i">
                <div class="border rounded-lg p-4">
                    <x-spire::skeleton variant="text" width="60%" class="mb-2" />
                    <x-spire::skeleton variant="text" width="80%" />
                    <x-spire::skeleton variant="text" width="40%" />
                </div>
            </template>
        </template>
    </div>

    <!-- Load More Button -->
    <x-spire::button
        x-show="!loading && hasMore"
        @click="loadMore"
        class="mt-4 w-full"
    >
        Carregar Mais
    </x-spire::button>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('infiniteScroll', () => ({
        items: [],
        loading: false,
        hasMore: true,
        page: 1,

        async loadMore() {
            this.loading = true;

            try {
                const response = await fetch(`/api/items?page=${this.page}`);
                const data = await response.json();

                this.items = [...this.items, ...data.items];
                this.hasMore = data.hasMore;
                this.page++;
            } catch (error) {
                console.error('Erro ao carregar:', error);
            } finally {
                this.loading = false;
            }
        },

        async init() {
            await this.loadMore();
        }
    }));
});
</script>
```

### Skeleton de Imagem

```blade
<div x-data="imageLoader">
    <div class="relative">
        <!-- Skeleton -->
        <x-spire::skeleton
            x-show="loading"
            variant="rectangular"
            width="400px"
            height="300px"
            class="rounded-lg"
        />

        <!-- Imagem real -->
        <img
            x-show="!loading"
            x-transition
            :src="imageSrc"
            class="w-full h-64 object-cover rounded-lg"
            @load="loading = false"
        />
    </div>

    <x-spire::skeleton
        x-show="loading"
        variant="text"
        width="50%"
        class="mt-2"
    />
    <h3 x-show="!loading" x-transition class="mt-2 text-lg font-semibold">
        Título da Imagem
    </h3>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('imageLoader', () => ({
        loading: true,
        imageSrc: 'https://picsum.photos/400/300?random=1'
    }));
});
</script>
```

## Acessibilidade

- **Screen Readers**: Ignorados pelos leitores de tela
- **Motion**: Respeita preferências de movimento reduzido
- **Focus**: Não interfere na navegação por teclado
- **Semantic**: Estrutura semântica adequada

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/skeleton-custom.css */
.spire-skeleton {
    @apply bg-gray-200 relative overflow-hidden;
}

.spire-skeleton--pulse {
    @apply animate-pulse;
}

.spire-skeleton--wave {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: wave 1.5s infinite;
}

.spire-skeleton--text {
    @apply rounded;
}

.spire-skeleton--rectangular {
    @apply rounded-md;
}

.spire-skeleton--circular {
    @apply rounded-full;
}

@keyframes wave {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
```

### Skeleton Customizado

```blade
<x-spire::skeleton class="custom-skeleton" variant="rectangular" />
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `animation-start` | Animação iniciou | - |
| `animation-end` | Animação terminou | - |

## Performance

- **CSS-only**: Animações puramente CSS
- **Lightweight**: Baixo impacto no DOM
- **GPU**: Animações otimizadas
- **Memory**: Sem JavaScript pesado

## Testes

**Cobertura**: 8 testes automatizados
- Renderização e variantes
- Animações e estados
- Acessibilidade
- Performance

## Relacionados

- [Table](table.md) - Para tabelas carregando
- [Card](card.md) - Para cards carregando
- [List](list.md) - Para listas carregando
- [Image](image.md) - Para imagens carregando