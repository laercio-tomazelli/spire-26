# Icon Component

O componente Icon fornece ícones Heroicon com fácil integração e customização.

## Uso Básico

```blade
<x-spire::icon name="user" />
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `name` | string | - | Nome do ícone Heroicon |
| `size` | string | `"md"` | Tamanho: `xs`, `sm`, `md`, `lg`, `xl` |
| `variant` | string | `"outline"` | Variante: `outline`, `solid` |
| `color` | string | `"currentColor"` | Cor do ícone |

## Exemplos

### Tamanhos

```blade
<x-spire::icon name="user" size="xs" />
<x-spire::icon name="user" size="sm" />
<x-spire::icon name="user" size="md" />
<x-spire::icon name="user" size="lg" />
<x-spire::icon name="user" size="xl" />
```

### Variantes

```blade
<x-spire::icon name="user" variant="outline" />
<x-spire::icon name="user" variant="solid" />
```

### Cores

```blade
<x-spire::icon name="user" color="text-blue-500" />
<x-spire::icon name="user" color="text-red-500" />
<x-spire::icon name="user" color="text-green-500" />
<x-spire::icon name="user" color="text-yellow-500" />
```

### Ícones Comuns

```blade
<!-- Navegação -->
<x-spire::icon name="home" />
<x-spire::icon name="chevron-left" />
<x-spire::icon name="chevron-right" />
<x-spire::icon name="arrow-left" />
<x-spire::icon name="arrow-right" />

<!-- Ações -->
<x-spire::icon name="plus" />
<x-spire::icon name="pencil" />
<x-spire::icon name="trash" />
<x-spire::icon name="eye" />
<x-spire::icon name="eye-slash" />

<!-- Status -->
<x-spire::icon name="check" />
<x-spire::icon name="x-mark" />
<x-spire::icon name="exclamation-triangle" />
<x-spire::icon name="information-circle" />

<!-- Comunicação -->
<x-spire::icon name="envelope" />
<x-spire::icon name="phone" />
<x-spire::icon name="chat-bubble-left" />
<x-spire::icon name="bell" />

<!-- Usuário -->
<x-spire::icon name="user" />
<x-spire::icon name="users" />
<x-spire::icon name="user-plus" />
<x-spire::icon name="user-minus" />
```

## Integração com Componentes

### Botões com Ícones

```blade
<x-spire::button>
    <x-spire::icon name="plus" class="mr-2" />
    Adicionar
</x-spire::button>

<x-spire::button variant="outline">
    <x-spire::icon name="pencil" class="mr-2" />
    Editar
</x-spire::button>

<x-spire::button variant="danger">
    <x-spire::icon name="trash" class="mr-2" />
    Excluir
</x-spire::button>
```

### Badges com Ícones

```blade
<x-spire::badge variant="success">
    <x-spire::icon name="check" class="mr-1" />
    Aprovado
</x-spire::badge>

<x-spire::badge variant="warning">
    <x-spire::icon name="exclamation-triangle" class="mr-1" />
    Pendente
</x-spire::badge>
```

### Tooltips com Ícones

```blade
<x-spire::tooltip content="Ajuda">
    <x-spire::icon name="question-mark-circle" class="cursor-help" />
</x-spire::tooltip>
```

### Navegação

```blade
<nav class="flex space-x-4">
    <a href="/" class="flex items-center space-x-2">
        <x-spire::icon name="home" />
        <span>Início</span>
    </a>

    <a href="/profile" class="flex items-center space-x-2">
        <x-spire::icon name="user" />
        <span>Perfil</span>
    </a>

    <a href="/settings" class="flex items-center space-x-2">
        <x-spire::icon name="cog" />
        <span>Configurações</span>
    </a>
</nav>
```

## Integração com Alpine.js

### Ícones Dinâmicos

```blade
<div x-data="dynamicIcon">
    <div class="flex items-center space-x-4">
        <x-spire::button @click="toggleFavorite">
            <x-spire::icon :name="isFavorite ? 'heart' : 'heart'" :color="isFavorite ? 'text-red-500' : 'text-gray-400'" />
        </x-spire::button>

        <x-spire::button @click="toggleLike">
            <x-spire::icon :name="isLiked ? 'hand-thumb-up' : 'hand-thumb-up'" :color="isLiked ? 'text-blue-500' : 'text-gray-400'" />
        </x-spire::button>

        <x-spire::button @click="toggleBookmark">
            <x-spire::icon :name="isBookmarked ? 'bookmark' : 'bookmark'" :color="isBookmarked ? 'text-yellow-500' : 'text-gray-400'" />
        </x-spire::button>
    </div>

    <p class="mt-4">
        Status:
        <x-spire::icon :name="statusIcon" :color="statusColor" class="ml-2" />
        <span x-text="statusText"></span>
    </p>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dynamicIcon', () => ({
        isFavorite: false,
        isLiked: false,
        isBookmarked: false,

        toggleFavorite() {
            this.isFavorite = !this.isFavorite;
        },

        toggleLike() {
            this.isLiked = !this.isLiked;
        },

        toggleBookmark() {
            this.isBookmarked = !this.isBookmarked;
        },

        get statusIcon() {
            if (this.isFavorite) return 'heart';
            if (this.isLiked) return 'hand-thumb-up';
            if (this.isBookmarked) return 'bookmark';
            return 'minus';
        },

        get statusColor() {
            if (this.isFavorite) return 'text-red-500';
            if (this.isLiked) return 'text-blue-500';
            if (this.isBookmarked) return 'text-yellow-500';
            return 'text-gray-400';
        },

        get statusText() {
            if (this.isFavorite) return 'Favoritado';
            if (this.isLiked) return 'Curtido';
            if (this.isBookmarked) return 'Salvo';
            return 'Neutro';
        }
    }));
});
</script>
```

### Loading States

```blade
<div x-data="loadingStates">
    <x-spire::button :disabled="loading" @click="saveData">
        <x-spire::icon
            :name="loading ? 'arrow-path' : 'cloud-arrow-up'"
            :class="loading ? 'animate-spin mr-2' : 'mr-2'"
        />
        <span x-text="loading ? 'Salvando...' : 'Salvar'"></span>
    </x-spire::button>

    <x-spire::button variant="outline" :disabled="syncing" @click="syncData">
        <x-spire::icon
            :name="syncing ? 'arrow-path' : 'arrow-right-circle'"
            :class="syncing ? 'animate-spin mr-2' : 'mr-2'"
        />
        <span x-text="syncing ? 'Sincronizando...' : 'Sincronizar'"></span>
    </x-spire::button>

    <div class="mt-4">
        <x-spire::icon
            :name="statusIcon"
            :color="statusColor"
            size="lg"
            class="mr-2"
        />
        <span x-text="statusMessage"></span>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('loadingStates', () => ({
        loading: false,
        syncing: false,

        async saveData() {
            this.loading = true;
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 2000));
            this.loading = false;
        },

        async syncData() {
            this.syncing = true;
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 3000));
            this.syncing = false;
        },

        get statusIcon() {
            if (this.loading) return 'clock';
            if (this.syncing) return 'arrow-path';
            return 'check-circle';
        },

        get statusColor() {
            if (this.loading || this.syncing) return 'text-blue-500';
            return 'text-green-500';
        },

        get statusMessage() {
            if (this.loading) return 'Salvando dados...';
            if (this.syncing) return 'Sincronizando...';
            return 'Tudo salvo!';
        }
    }));
});
</script>
```

### Menu de Ações

```blade
<div x-data="actionMenu">
    <x-spire::dropdown>
        <x-spire::dropdown-trigger>
            <x-spire::button variant="ghost" size="sm">
                <x-spire::icon name="ellipsis-vertical" />
            </x-spire::button>
        </x-spire::dropdown-trigger>

        <x-spire::dropdown-content>
            <x-spire::dropdown-item @click="edit">
                <x-spire::icon name="pencil" class="mr-2" />
                Editar
            </x-spire::dropdown-item>

            <x-spire::dropdown-item @click="duplicate">
                <x-spire::icon name="document-duplicate" class="mr-2" />
                Duplicar
            </x-spire::dropdown-item>

            <x-spire::dropdown-item @click="share">
                <x-spire::icon name="share" class="mr-2" />
                Compartilhar
            </x-spire::dropdown-item>

            <x-spire::dropdown-separator />

            <x-spire::dropdown-item variant="danger" @click="delete">
                <x-spire::icon name="trash" class="mr-2" />
                Excluir
            </x-spire::dropdown-item>
        </x-spire::dropdown-content>
    </x-spire::dropdown>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('actionMenu', () => ({
        edit() {
            console.log('Editar item');
        },

        duplicate() {
            console.log('Duplicar item');
        },

        share() {
            console.log('Compartilhar item');
        },

        delete() {
            if (confirm('Tem certeza que deseja excluir?')) {
                console.log('Excluir item');
            }
        }
    }));
});
</script>
```

### Navegação com Estados

```blade
<div x-data="navigation">
    <nav class="flex space-x-4">
        <a
            href="/"
            :class="currentPage === 'home' ? 'text-blue-600' : 'text-gray-600'"
            @click="currentPage = 'home'"
        >
            <x-spire::icon name="home" class="mr-1" />
            Início
        </a>

        <a
            href="/projects"
            :class="currentPage === 'projects' ? 'text-blue-600' : 'text-gray-600'"
            @click="currentPage = 'projects'"
        >
            <x-spire::icon name="folder" class="mr-1" />
            Projetos
        </a>

        <a
            href="/team"
            :class="currentPage === 'team' ? 'text-blue-600' : 'text-gray-600'"
            @click="currentPage = 'team'"
        >
            <x-spire::icon name="users" class="mr-1" />
            Equipe
        </a>

        <a
            href="/settings"
            :class="currentPage === 'settings' ? 'text-blue-600' : 'text-gray-600'"
            @click="currentPage = 'settings'"
        >
            <x-spire::icon name="cog" class="mr-1" />
            Configurações
        </a>
    </nav>

    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4" x-text="pageTitle"></h2>

        <div class="flex items-center space-x-4">
            <x-spire::icon :name="pageIcon" size="lg" color="text-blue-500" />
            <p x-text="pageDescription"></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('navigation', () => ({
        currentPage: 'home',

        get pageTitle() {
            const titles = {
                home: 'Página Inicial',
                projects: 'Meus Projetos',
                team: 'Equipe',
                settings: 'Configurações'
            };
            return titles[this.currentPage] || 'Página Inicial';
        },

        get pageIcon() {
            const icons = {
                home: 'home',
                projects: 'folder',
                team: 'users',
                settings: 'cog'
            };
            return icons[this.currentPage] || 'home';
        },

        get pageDescription() {
            const descriptions = {
                home: 'Bem-vindo ao painel principal',
                projects: 'Gerencie seus projetos e tarefas',
                team: 'Veja informações da equipe',
                settings: 'Personalize suas preferências'
            };
            return descriptions[this.currentPage] || 'Selecione uma página';
        }
    }));
});
</script>
```

## Acessibilidade

- **Decorative**: Ícones decorativos são ocultados dos leitores de tela
- **Semantic**: Ícones com significado têm labels apropriadas
- **Focus**: Não interferem na navegação por teclado
- **Alt Text**: Suporte para texto alternativo quando necessário

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/icon-custom.css */
.spire-icon {
    @apply inline-block flex-shrink-0;
}

.spire-icon--xs {
    @apply w-3 h-3;
}

.spire-icon--sm {
    @apply w-4 h-4;
}

.spire-icon--md {
    @apply w-5 h-5;
}

.spire-icon--lg {
    @apply w-6 h-6;
}

.spire-icon--xl {
    @apply w-8 h-8;
}
```

### Ícone Customizado

```blade
<x-spire::icon class="custom-icon" name="user" />
```

## Lista de Ícones Disponíveis

### Outline Icons (24x24)
- `academic-cap`, `adjustments-horizontal`, `adjustments-vertical`
- `archive-box`, `archive-box-arrow-down`, `archive-box-x-mark`
- `arrow-down`, `arrow-down-circle`, `arrow-down-left`, `arrow-down-right`
- `arrow-left`, `arrow-left-circle`, `arrow-left-end-on-rectangle`
- `arrow-right`, `arrow-right-circle`, `arrow-right-end-on-rectangle`
- `arrow-up`, `arrow-up-circle`, `arrow-up-left`, `arrow-up-right`
- `arrows-right-left`, `arrows-up-down`
- `at-symbol`, `backspace`, `backward`, `banknotes`
- `bars-2`, `bars-3`, `bars-4`, `bars-arrow-down`, `bars-arrow-up`
- `battery-0`, `battery-100`, `battery-50`
- `bell`, `bell-alert`, `bell-slash`, `bell-snooze`
- `bolt`, `bolt-slash`
- `book-open`, `bookmark`, `bookmark-slash`, `bookmark-square`
- `briefcase`, `bug-ant`
- `building-library`, `building-office`, `building-storefront`
- `cake`, `calculator`, `calendar`, `calendar-days`
- `camera`, `chart-bar`, `chart-bar-square`, `chart-pie`
- `chat-bubble-bottom-center`, `chat-bubble-left`, `chat-bubble-left-right`
- `check`, `check-badge`, `check-circle`
- `chevron-down`, `chevron-left`, `chevron-right`, `chevron-up`
- `circle-stack`, `clipboard`, `clipboard-document`, `clipboard-document-check`
- `clock`, `cloud`, `cloud-arrow-down`, `cloud-arrow-up`
- `code-bracket`, `code-bracket-square`
- `cog`, `cog-6-tooth`, `command-line`
- `computer-desktop`, `cpu-chip`
- `credit-card`, `cube`, `cube-transparent`
- `currency-dollar`, `currency-euro`, `currency-pound`, `currency-yen`
- `cursor-arrow-rays`, `cursor-arrow-ripple`
- `device-phone-mobile`, `device-tablet`
- `document`, `document-arrow-down`, `document-arrow-up`
- `document-chart-bar`, `document-check`, `document-duplicate`
- `document-magnifying-glass`, `document-minus`, `document-plus`
- `document-text`, `ellipsis-horizontal`, `ellipsis-vertical`
- `envelope`, `envelope-open`, `exclamation-circle`, `exclamation-triangle`
- `eye`, `eye-dropper`, `eye-slash`
- `face-frown`, `face-smile`
- `finger-print`, `fire`, `flag`, `folder`, `folder-arrow-down`
- `folder-minus`, `folder-open`, `folder-plus`
- `forward`, `funnel`, `gif`, `gift`, `gift-top`
- `globe-americas`, `globe-asia-australia`, `globe-europe-africa`
- `hand-raised`, `hand-thumb-down`, `hand-thumb-up`
- `hashtag`, `heart`, `home`, `home-modern`
- `identification`, `inbox`, `inbox-arrow-down`, `inbox-stack`
- `information-circle`, `key`, `language`
- `lifebuoy`, `light-bulb`, `link`, `list-bullet`
- `lock-closed`, `lock-open`
- `magnifying-glass`, `magnifying-glass-circle`, `magnifying-glass-minus`
- `magnifying-glass-plus`, `map`, `map-pin`
- `megaphone`, `microphone`, `minus`, `minus-circle`
- `moon`, `musical-note`
- `newspaper`, `no-symbol`
- `paint-brush`, `paper-airplane`, `paper-clip`
- `pause`, `pause-circle`, `pencil`, `pencil-square`
- `phone`, `phone-arrow-down-left`, `phone-arrow-up-right`, `phone-x-mark`
- `photo`, `play`, `play-circle`, `play-pause`
- `plus`, `plus-circle`, `power`
- `presentation-chart-bar`, `presentation-chart-line`
- `printer`, `puzzle-piece`
- `qr-code`, `question-mark-circle`
- `radio`, `receipt-percent`, `receipt-refund`
- `rectangle-group`, `rectangle-stack`
- `rocket-launch`, `rss`
- `scale`, `scissors`, `server`, `server-stack`
- `share`, `shield-check`, `shield-exclamation`
- `shopping-bag`, `shopping-cart`
- `signal`, `signal-slash`, `sparkles`
- `speaker-wave`, `speaker-x-mark`, `square-2-stack`, `square-3-stack-vertical`
- `squares-2x2`, `squares-plus`
- `star`, `stop`, `stop-circle`
- `sun`, `swatch`
- `table-cells`, `tag`, `ticket`
- `trash`, `trophy`, `truck`
- `tv`, `user`, `user-circle`, `user-group`, `user-minus`, `user-plus`
- `users`, `variable`
- `video-camera`, `video-camera-slash`
- `viewfinder-circle`, `wallet`, `wifi`
- `window`, `wrench`, `wrench-screwdriver`
- `x-mark`, `x-circle`

### Solid Icons (24x24)
- `academic-cap`, `adjustments-horizontal`, `adjustments-vertical`
- `archive-box`, `archive-box-arrow-down`, `archive-box-x-mark`
- `arrow-down`, `arrow-down-circle`, `arrow-down-left`, `arrow-down-right`
- `arrow-left`, `arrow-left-circle`, `arrow-left-end-on-rectangle`
- `arrow-right`, `arrow-right-circle`, `arrow-right-end-on-rectangle`
- `arrow-up`, `arrow-up-circle`, `arrow-up-left`, `arrow-up-right`
- `arrows-right-left`, `arrows-up-down`
- `at-symbol`, `backspace`, `backward`, `banknotes`
- `bars-2`, `bars-3`, `bars-3-bottom-left`, `bars-3-bottom-right`, `bars-4`
- `bars-arrow-down`, `bars-arrow-up`
- `battery-0`, `battery-100`, `battery-50`
- `bell`, `bell-alert`, `bell-slash`, `bell-snooze`
- `bolt`, `bolt-slash`
- `book-open`, `bookmark`, `bookmark-slash`, `bookmark-square`
- `briefcase`, `bug-ant`
- `building-library`, `building-office`, `building-storefront`
- `cake`, `calculator`, `calendar`, `calendar-days`
- `camera`, `chart-bar`, `chart-bar-square`, `chart-pie`
- `chat-bubble-bottom-center`, `chat-bubble-left`, `chat-bubble-left-right`
- `check`, `check-badge`, `check-circle`
- `chevron-down`, `chevron-left`, `chevron-right`, `chevron-up`
- `circle-stack`, `clipboard`, `clipboard-document`, `clipboard-document-check`
- `clock`, `cloud`, `cloud-arrow-down`, `cloud-arrow-up`
- `code-bracket`, `code-bracket-square`
- `cog`, `cog-6-tooth`, `command-line`
- `computer-desktop`, `cpu-chip`
- `credit-card`, `cube`, `cube-transparent`
- `currency-dollar`, `currency-euro`, `currency-pound`, `currency-yen`
- `cursor-arrow-rays`, `cursor-arrow-ripple`
- `device-phone-mobile`, `device-tablet`
- `document`, `document-arrow-down`, `document-arrow-up`
- `document-chart-bar`, `document-check`, `document-duplicate`
- `document-magnifying-glass`, `document-minus`, `document-plus`
- `document-text`, `ellipsis-horizontal`, `ellipsis-vertical`
- `envelope`, `envelope-open`, `exclamation-circle`, `exclamation-triangle`
- `eye`, `eye-dropper`, `eye-slash`
- `face-frown`, `face-smile`
- `finger-print`, `fire`, `flag`, `folder`, `folder-arrow-down`
- `folder-minus`, `folder-open`, `folder-plus`
- `forward`, `funnel`, `gif`, `gift`, `gift-top`
- `globe-americas`, `globe-asia-australia`, `globe-europe-australia`
- `hand-raised`, `hand-thumb-down`, `hand-thumb-up`
- `hashtag`, `heart`, `home`, `home-modern`
- `identification`, `inbox`, `inbox-arrow-down`, `inbox-stack`
- `information-circle`, `key`, `language`
- `lifebuoy`, `light-bulb`, `link`, `list-bullet`
- `lock-closed`, `lock-open`
- `magnifying-glass`, `magnifying-glass-circle`, `magnifying-glass-minus`
- `magnifying-glass-plus`, `map`, `map-pin`
- `megaphone`, `microphone`, `minus`, `minus-circle`
- `moon`, `musical-note`
- `newspaper`, `no-symbol`
- `paint-brush`, `paper-airplane`, `paper-clip`
- `pause`, `pause-circle`, `pencil`, `pencil-square`
- `phone`, `phone-arrow-down-left`, `phone-arrow-up-right`, `phone-x-mark`
- `photo`, `play`, `play-circle`, `play-pause`
- `plus`, `plus-circle`, `power`
- `presentation-chart-bar`, `presentation-chart-line`
- `printer`, `puzzle-piece`
- `qr-code`, `question-mark-circle`
- `radio`, `receipt-percent`, `receipt-refund`
- `rectangle-group`, `rectangle-stack`
- `rocket-launch`, `rss`
- `scale`, `scissors`, `server`, `server-stack`
- `share`, `shield-check`, `shield-exclamation`
- `shopping-bag`, `shopping-cart`
- `signal`, `signal-slash`, `sparkles`
- `speaker-wave`, `speaker-x-mark`, `square-2-stack`, `square-3-stack-vertical`
- `squares-2x2`, `squares-plus`
- `star`, `stop`, `stop-circle`
- `sun`, `swatch`
- `table-cells`, `tag`, `ticket`
- `trash`, `trophy`, `truck`
- `tv`, `user`, `user-circle`, `user-group`, `user-minus`, `user-plus`
- `users`, `variable`
- `video-camera`, `video-camera-slash`
- `viewfinder-circle`, `wallet`, `wifi`
- `window`, `wrench`, `wrench-screwdriver`
- `x-mark`, `x-circle`

## Performance

- **SVG Inline**: Ícones são SVGs inline para melhor performance
- **Tree Shaking**: Apenas ícones usados são incluídos no bundle
- **Caching**: Ícones são cacheados pelo navegador
- **Lightweight**: Biblioteca Heroicon é muito leve

## Testes

**Cobertura**: 6 testes automatizados
- Renderização e propriedades
- Tamanhos e variantes
- Acessibilidade
- Performance

## Relacionados

- [Button](button.md) - Para botões com ícones
- [Badge](badge.md) - Para badges com ícones
- [Tooltip](tooltip.md) - Para dicas com ícones
- [Dropdown](dropdown.md) - Para menus com ícones