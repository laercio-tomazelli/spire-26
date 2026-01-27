# Badge Component

O componente Badge fornece indicadores visuais compactos para status, contadores e labels.

## Uso Básico

```blade
<x-spire::badge>Badge</x-spire::badge>
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `variant` | string | `"default"` | Variante: `default`, `primary`, `secondary`, `success`, `warning`, `danger` |
| `size` | string | `"md"` | Tamanho: `xs`, `sm`, `md`, `lg` |
| `rounded` | boolean | `false` | Badge totalmente arredondado |
| `dot` | boolean | `false` | Apenas indicador circular |
| `removable` | boolean | `false` | Permite remoção |
| `href` | string | - | Link para navegação |

## Exemplos

### Variantes

```blade
<x-spire::badge variant="default">Padrão</x-spire::badge>
<x-spire::badge variant="primary">Primário</x-spire::badge>
<x-spire::badge variant="secondary">Secundário</x-spire::badge>
<x-spire::badge variant="success">Sucesso</x-spire::badge>
<x-spire::badge variant="warning">Aviso</x-spire::badge>
<x-spire::badge variant="danger">Perigo</x-spire::badge>
```

### Tamanhos

```blade
<x-spire::badge size="xs">XS</x-spire::badge>
<x-spire::badge size="sm">SM</x-spire::badge>
<x-spire::badge size="md">MD</x-spire::badge>
<x-spire::badge size="lg">LG</x-spire::badge>
```

### Badge Arredondado

```blade
<x-spire::badge rounded>Novo</x-spire::badge>
<x-spire::badge rounded variant="success">Ativo</x-spire::badge>
```

### Badge Ponto

```blade
<x-spire::badge dot variant="success"></x-spire::badge>
<x-spire::badge dot variant="warning"></x-spire::badge>
<x-spire::badge dot variant="danger"></x-spire::badge>
```

### Com Ícones

```blade
<x-spire::badge variant="success">
    <x-spire::icon name="check" class="mr-1" />
    Aprovado
</x-spire::badge>

<x-spire::badge variant="warning">
    <x-spire::icon name="exclamation-triangle" class="mr-1" />
    Pendente
</x-spire::badge>

<x-spire::badge variant="danger">
    <x-spire::icon name="x-mark" class="mr-1" />
    Rejeitado
</x-spire::badge>
```

### Contadores

```blade
<x-spire::badge variant="primary">5</x-spire::badge>
<x-spire::badge variant="success">99+</x-spire::badge>
<x-spire::badge variant="warning">!</x-spire::badge>
```

## Casos de Uso

### Status de Pedidos

```blade
<div class="space-y-2">
    <div class="flex items-center justify-between p-4 border rounded">
        <span>Pedido #12345</span>
        <x-spire::badge variant="success">Aprovado</x-spire::badge>
    </div>

    <div class="flex items-center justify-between p-4 border rounded">
        <span>Pedido #12346</span>
        <x-spire::badge variant="warning">Pendente</x-spire::badge>
    </div>

    <div class="flex items-center justify-between p-4 border rounded">
        <span>Pedido #12347</span>
        <x-spire::badge variant="danger">Cancelado</x-spire::badge>
    </div>
</div>
```

### Notificações

```blade
<div class="flex items-center space-x-4">
    <x-spire::button variant="ghost">
        <x-spire::icon name="bell" />
        <x-spire::badge
            variant="danger"
            size="xs"
            class="absolute -top-1 -right-1"
        >
            3
        </x-spire::badge>
    </x-spire::button>

    <x-spire::button variant="ghost">
        <x-spire::icon name="envelope" />
        <x-spire::badge
            variant="primary"
            size="xs"
            class="absolute -top-1 -right-1"
        >
            12
        </x-spire::badge>
    </x-spire::button>
</div>
```

### Tags

```blade
<div class="flex flex-wrap gap-2">
    <x-spire::badge variant="primary">React</x-spire::badge>
    <x-spire::badge variant="secondary">Vue.js</x-spire::badge>
    <x-spire::badge variant="success">Laravel</x-spire::badge>
    <x-spire::badge variant="warning">PHP</x-spire::badge>
    <x-spire::badge variant="danger">JavaScript</x-spire::badge>
</div>
```

### Status Online

```blade
<div class="flex items-center space-x-2">
    <x-spire::badge dot variant="success"></x-spire::badge>
    <span>Online</span>
</div>

<div class="flex items-center space-x-2">
    <x-spire::badge dot variant="warning"></x-spire::badge>
    <span>Ausente</span>
</div>

<div class="flex items-center space-x-2">
    <x-spire::badge dot variant="secondary"></x-spire::badge>
    <span>Offline</span>
</div>
```

## Integração com Alpine.js

### Badges Dinâmicos

```blade
<div x-data="notificationCenter">
    <div class="flex items-center space-x-4">
        <x-spire::button @click="markAsRead('messages')" class="relative">
            <x-spire::icon name="envelope" />
            <x-spire::badge
                x-show="unreadMessages > 0"
                variant="primary"
                size="xs"
                class="absolute -top-1 -right-1"
                x-text="unreadMessages"
            ></x-spire::badge>
        </x-spire::button>

        <x-spire::button @click="markAsRead('notifications')" class="relative">
            <x-spire::icon name="bell" />
            <x-spire::badge
                x-show="unreadNotifications > 0"
                variant="danger"
                size="xs"
                class="absolute -top-1 -right-1"
                x-text="unreadNotifications"
            ></x-spire::badge>
        </x-spire::button>

        <x-spire::button @click="toggleStatus">
            <x-spire::badge
                :variant="isOnline ? 'success' : 'secondary'"
                dot
                class="mr-2"
            ></x-spire::badge>
            <span x-text="isOnline ? 'Online' : 'Offline'"></span>
        </x-spire::button>
    </div>

    <div class="mt-4">
        <p>Status: <x-spire::badge :variant="statusColor" x-text="statusText"></x-spire::badge></p>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationCenter', () => ({
        unreadMessages: 5,
        unreadNotifications: 12,
        isOnline: true,

        markAsRead(type) {
            if (type === 'messages') {
                this.unreadMessages = 0;
            } else {
                this.unreadNotifications = 0;
            }
        },

        toggleStatus() {
            this.isOnline = !this.isOnline;
        },

        get statusColor() {
            if (this.unreadMessages > 10 || this.unreadNotifications > 10) {
                return 'danger';
            } else if (this.unreadMessages > 5 || this.unreadNotifications > 5) {
                return 'warning';
            } else if (this.unreadMessages > 0 || this.unreadNotifications > 0) {
                return 'primary';
            } else {
                return 'success';
            }
        },

        get statusText() {
            const total = this.unreadMessages + this.unreadNotifications;
            if (total === 0) return 'Tudo lido';
            return `${total} não lido${total > 1 ? 's' : ''}`;
        }
    }));
});
</script>
```

### Tags Removíveis

```blade
<div x-data="tagManager">
    <div class="flex flex-wrap gap-2 mb-4">
        <template x-for="tag in tags" :key="tag.id">
            <x-spire::badge
                :variant="tag.color"
                removable
                @remove="removeTag(tag.id)"
            >
                <span x-text="tag.name"></span>
            </x-spire::badge>
        </template>
    </div>

    <x-spire::form-group label="Adicionar Tag">
        <div class="flex gap-2">
            <x-spire::input
                x-model="newTag"
                @keydown.enter="addTag"
                placeholder="Nome da tag"
            />
            <x-spire::select x-model="tagColor" :options="colorOptions" />
            <x-spire::button @click="addTag">Adicionar</x-spire::button>
        </div>
    </x-spire::form-group>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tagManager', () => ({
        tags: [
            { id: 1, name: 'Urgente', color: 'danger' },
            { id: 2, name: 'Trabalho', color: 'primary' },
            { id: 3, name: 'Pessoal', color: 'secondary' }
        ],
        newTag: '',
        tagColor: 'default',
        colorOptions: [
            { value: 'default', label: 'Padrão' },
            { value: 'primary', label: 'Primário' },
            { value: 'secondary', label: 'Secundário' },
            { value: 'success', label: 'Sucesso' },
            { value: 'warning', label: 'Aviso' },
            { value: 'danger', label: 'Perigo' }
        ],
        nextId: 4,

        addTag() {
            if (this.newTag.trim()) {
                this.tags.push({
                    id: this.nextId++,
                    name: this.newTag.trim(),
                    color: this.tagColor
                });
                this.newTag = '';
            }
        },

        removeTag(id) {
            this.tags = this.tags.filter(tag => tag.id !== id);
        }
    }));
});
</script>
```

### Status de Tarefas

```blade
<div x-data="taskManager">
    <div class="space-y-2">
        <template x-for="task in tasks" :key="task.id">
            <div class="flex items-center justify-between p-3 border rounded">
                <div class="flex items-center space-x-3">
                    <input
                        type="checkbox"
                        :checked="task.completed"
                        @change="toggleTask(task.id)"
                        class="rounded"
                    />
                    <span :class="{ 'line-through text-gray-500': task.completed }" x-text="task.title"></span>
                </div>

                <x-spire::badge
                    :variant="getStatusVariant(task.status)"
                    size="sm"
                    x-text="getStatusText(task.status)"
                ></x-spire::badge>
            </div>
        </template>
    </div>

    <div class="mt-4 flex gap-2">
        <x-spire::button @click="filterTasks('all')">Todas</x-spire::button>
        <x-spire::button @click="filterTasks('pending')">Pendentes</x-spire::button>
        <x-spire::button @click="filterTasks('completed')">Concluídas</x-spire::button>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('taskManager', () => ({
        tasks: [
            { id: 1, title: 'Revisar código', completed: false, status: 'pending' },
            { id: 2, title: 'Escrever documentação', completed: true, status: 'completed' },
            { id: 3, title: 'Testar aplicação', completed: false, status: 'in-progress' }
        ],
        filter: 'all',

        toggleTask(id) {
            const task = this.tasks.find(t => t.id === id);
            if (task) {
                task.completed = !task.completed;
                task.status = task.completed ? 'completed' : 'pending';
            }
        },

        filterTasks(status) {
            this.filter = status;
        },

        get filteredTasks() {
            if (this.filter === 'all') return this.tasks;
            if (this.filter === 'pending') return this.tasks.filter(t => !t.completed);
            if (this.filter === 'completed') return this.tasks.filter(t => t.completed);
            return this.tasks;
        },

        getStatusVariant(status) {
            switch (status) {
                case 'completed': return 'success';
                case 'in-progress': return 'warning';
                case 'pending': return 'secondary';
                default: return 'default';
            }
        },

        getStatusText(status) {
            switch (status) {
                case 'completed': return 'Concluída';
                case 'in-progress': return 'Em Andamento';
                case 'pending': return 'Pendente';
                default: return 'Desconhecido';
            }
        }
    }));
});
</script>
```

### Badge de Progresso

```blade
<div x-data="progressTracker">
    <div class="space-y-4">
        <div>
            <div class="flex justify-between items-center mb-2">
                <span>Progresso do Projeto</span>
                <x-spire::badge variant="primary" x-text="`${progress}%`"></x-spire::badge>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div
                    class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                    :style="`width: ${progress}%`"
                ></div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <x-spire::badge
                    :variant="tasksCompleted > 5 ? 'success' : 'warning'"
                    rounded
                    class="text-lg px-3 py-1 mb-1"
                    x-text="tasksCompleted"
                ></x-spire::badge>
                <div class="text-sm text-gray-600">Tarefas</div>
            </div>

            <div class="text-center">
                <x-spire::badge
                    :variant="bugsFixed > 3 ? 'success' : 'danger'"
                    rounded
                    class="text-lg px-3 py-1 mb-1"
                    x-text="bugsFixed"
                ></x-spire::badge>
                <div class="text-sm text-gray-600">Bugs Corrigidos</div>
            </div>

            <div class="text-center">
                <x-spire::badge
                    variant="primary"
                    rounded
                    class="text-lg px-3 py-1 mb-1"
                    x-text="commits"
                ></x-spire::badge>
                <div class="text-sm text-gray-600">Commits</div>
            </div>

            <div class="text-center">
                <x-spire::badge
                    :variant="daysLeft <= 3 ? 'danger' : 'warning'"
                    rounded
                    class="text-lg px-3 py-1 mb-1"
                    x-text="daysLeft"
                ></x-spire::badge>
                <div class="text-sm text-gray-600">Dias Restantes</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('progressTracker', () => ({
        progress: 75,
        tasksCompleted: 8,
        bugsFixed: 5,
        commits: 24,
        daysLeft: 5
    }));
});
</script>
```

## Acessibilidade

- **Screen Readers**: Texto alternativo adequado
- **Focus**: Navegação por teclado quando removível
- **Colors**: Contraste adequado para todas as variantes
- **Semantic**: Estrutura semântica apropriada

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/badge-custom.css */
.spire-badge {
    @apply inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full;
}

.spire-badge--default {
    @apply bg-gray-100 text-gray-800;
}

.spire-badge--primary {
    @apply bg-blue-100 text-blue-800;
}

.spire-badge--success {
    @apply bg-green-100 text-green-800;
}

.spire-badge--warning {
    @apply bg-yellow-100 text-yellow-800;
}

.spire-badge--danger {
    @apply bg-red-100 text-red-800;
}

.spire-badge--dot {
    @apply w-2 h-2 p-0 rounded-full;
}

.spire-badge--removable:hover {
    @apply cursor-pointer;
}
```

### Badge Customizado

```blade
<x-spire::badge class="custom-badge" variant="primary">Custom</x-spire::badge>
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `remove` | Badge removido | `{ badge: Element }` |
| `click` | Badge clicado | `{ badge: Element }` |

## Performance

- **Lightweight**: Componente muito leve
- **CSS-only**: Estilos puramente CSS
- **No JS**: Não requer JavaScript para funcionamento básico
- **Optimized**: Renderização otimizada

## Testes

**Cobertura**: 10 testes automatizados
- Renderização e variantes
- Estados e interações
- Acessibilidade
- Performance

## Relacionados

- [Button](button.md) - Para ações
- [Icon](icon.md) - Para ícones nos badges
- [Tooltip](tooltip.md) - Para dicas adicionais
- [Avatar](avatar.md) - Para indicadores de usuário