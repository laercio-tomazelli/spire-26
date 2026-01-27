# Toast Component

O componente Toast fornece notificações temporárias não-intrusivas para feedback do usuário.

## Uso Básico

```blade
<x-spire::toast message="Operação realizada com sucesso!" />
```

## Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `message` | string | - | Mensagem do toast |
| `type` | string | `"info"` | Tipo: `success`, `error`, `warning`, `info` |
| `duration` | number | `4000` | Duração em ms (0 = permanente) |
| `position` | string | `"top-right"` | Posição: `top-left`, `top-right`, `bottom-left`, `bottom-right` |
| `dismissible` | boolean | `true` | Permite fechar manualmente |
| `show` | boolean | `true` | Controla visibilidade |
| `icon` | string | - | Ícone customizado (nome Heroicon) |

## Exemplos

### Tipos de Toast

```blade
<x-spire::toast message="Operação realizada com sucesso!" type="success" />
<x-spire::toast message="Erro ao salvar dados" type="error" />
<x-spire::toast message="Atenção: dados não salvos" type="warning" />
<x-spire::toast message="Informação importante" type="info" />
```

### Posicionamento

```blade
<x-spire::toast message="Topo esquerdo" position="top-left" />
<x-spire::toast message="Topo direito" position="top-right" />
<x-spire::toast message="Baixo esquerdo" position="bottom-left" />
<x-spire::toast message="Baixo direito" position="bottom-right" />
```

### Com Ícones Customizados

```blade
<x-spire::toast
    message="Arquivo enviado"
    type="success"
    icon="cloud-arrow-up"
/>

<x-spire::toast
    message="Erro de conexão"
    type="error"
    icon="wifi"
/>
```

### Toast Permanente

```blade
<x-spire::toast
    message="Mensagem importante - clique para fechar"
    duration="0"
    type="warning"
/>
```

### Sem Botão de Fechar

```blade
<x-spire::toast
    message="Esta mensagem some automaticamente"
    dismissible="false"
    duration="3000"
/>
```

## Sistema de Toasts

### Container de Toasts

```blade
<x-spire::toast-container position="top-right">
    <!-- Toasts serão adicionados aqui automaticamente -->
</x-spire::toast-container>
```

### Múltiplos Toasts

```blade
<x-spire::toast-container position="top-right">
    <x-spire::toast message="Primeira mensagem" type="success" />
    <x-spire::toast message="Segunda mensagem" type="info" />
    <x-spire::toast message="Terceira mensagem" type="warning" />
</x-spire::toast-container>
```

## Integração com Alpine.js

### Controle Programático

```blade
<div x-data="toastController">
    <div class="space-x-2 mb-4">
        <x-spire::button @click="showSuccess">Sucesso</x-spire::button>
        <x-spire::button @click="showError">Erro</x-spire::button>
        <x-spire::button @click="showWarning">Aviso</x-spire::button>
        <x-spire::button @click="showInfo">Info</x-spire::button>
    </div>

    <x-spire::toast-container position="top-right">
        <template x-for="toast in toasts" :key="toast.id">
            <x-spire::toast
                :message="toast.message"
                :type="toast.type"
                :show="toast.show"
                @close="removeToast(toast.id)"
            />
        </template>
    </x-spire::toast-container>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('toastController', () => ({
        toasts: [],
        toastId: 0,

        showSuccess() {
            this.addToast('Operação realizada com sucesso!', 'success');
        },

        showError() {
            this.addToast('Erro ao processar solicitação', 'error');
        },

        showWarning() {
            this.addToast('Atenção: verifique os dados', 'warning');
        },

        showInfo() {
            this.addToast('Nova atualização disponível', 'info');
        },

        addToast(message, type) {
            const id = ++this.toastId;
            this.toasts.push({
                id,
                message,
                type,
                show: true
            });

            // Auto-remove after 4 seconds
            setTimeout(() => {
                this.removeToast(id);
            }, 4000);
        },

        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        }
    }));
});
</script>
```

### Toast de Formulário

```blade
<div x-data="formWithToast">
    <form @submit.prevent="submitForm">
        <x-spire::form-group label="Nome">
            <x-spire::input x-model="name" required />
        </x-spire::form-group>

        <x-spire::form-group label="Email">
            <x-spire::input type="email" x-model="email" required />
        </x-spire::form-group>

        <x-spire::button type="submit" :disabled="loading">
            <span x-show="!loading">Enviar</span>
            <span x-show="loading">Enviando...</span>
        </x-spire::button>
    </form>

    <x-spire::toast-container position="top-right">
        <x-spire::toast
            x-show="successToast"
            message="Formulário enviado com sucesso!"
            type="success"
            @close="successToast = false"
        />

        <x-spire::toast
            x-show="errorToast"
            message="Erro ao enviar formulário"
            type="error"
            @close="errorToast = false"
        />
    </x-spire::toast-container>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('formWithToast', () => ({
        name: '',
        email: '',
        loading: false,
        successToast: false,
        errorToast: false,

        async submitForm() {
            this.loading = true;
            this.successToast = false;
            this.errorToast = false;

            try {
                const response = await fetch('/api/contact', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: this.name,
                        email: this.email
                    })
                });

                if (response.ok) {
                    this.successToast = true;
                    this.name = '';
                    this.email = '';
                } else {
                    throw new Error('Erro na requisição');
                }
            } catch (error) {
                this.errorToast = true;
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>
```

### Toast de Progresso

```blade
<div x-data="progressToast">
    <x-spire::button @click="startProcess">Iniciar Processo</x-spire::button>

    <x-spire::toast-container position="bottom-right">
        <x-spire::toast
            x-show="showProgressToast"
            :message="`Processando... ${progress}%`"
            type="info"
            duration="0"
            dismissible="false"
        >
            <x-slot:progress>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div
                        class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                        :style="`width: ${progress}%`"
                    ></div>
                </div>
            </x-slot:progress>
        </x-spire::toast>

        <x-spire::toast
            x-show="showSuccessToast"
            message="Processo concluído!"
            type="success"
            @close="showSuccessToast = false"
        />
    </x-spire::toast-container>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('progressToast', () => ({
        showProgressToast: false,
        showSuccessToast: false,
        progress: 0,
        interval: null,

        startProcess() {
            this.showProgressToast = true;
            this.showSuccessToast = false;
            this.progress = 0;

            this.interval = setInterval(() => {
                this.progress += 10;
                if (this.progress >= 100) {
                    clearInterval(this.interval);
                    this.showProgressToast = false;
                    this.showSuccessToast = true;
                }
            }, 500);
        }
    }));
});
</script>
```

### Toast de Confirmação

```blade
<div x-data="confirmationToast">
    <x-spire::button @click="deleteItem" variant="danger">
        Excluir Item
    </x-spire::button>

    <x-spire::toast-container position="top-center">
        <x-spire::toast
            x-show="showConfirmToast"
            message="Tem certeza que deseja excluir?"
            type="warning"
            duration="0"
        >
            <x-slot:actions>
                <div class="flex space-x-2 mt-3">
                    <x-spire::button size="sm" @click="confirmDelete" variant="danger">
                        Sim, excluir
                    </x-spire::button>
                    <x-spire::button size="sm" variant="outline" @click="cancelDelete">
                        Cancelar
                    </x-spire::button>
                </div>
            </x-slot:actions>
        </x-spire::toast>

        <x-spire::toast
            x-show="showDeletedToast"
            message="Item excluído com sucesso"
            type="success"
            @close="showDeletedToast = false"
        />
    </x-spire::toast-container>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('confirmationToast', () => ({
        showConfirmToast: false,
        showDeletedToast: false,

        deleteItem() {
            this.showConfirmToast = true;
        },

        confirmDelete() {
            this.showConfirmToast = false;
            // Simular exclusão
            setTimeout(() => {
                this.showDeletedToast = true;
            }, 500);
        },

        cancelDelete() {
            this.showConfirmToast = false;
        }
    }));
});
</script>
```

## Acessibilidade

- **ARIA**: `role="alert"` para mensagens importantes
- **Leitores de Tela**: Anúncios automáticos
- **Foco**: Não rouba foco da interface
- **Navegação**: Não interfere na navegação
- **Contraste**: Cores com bom contraste

## Estilização Customizada

### Tema Customizado

```css
/* resources/css/toast-custom.css */
.spire-toast {
    @apply fixed z-50 max-w-sm w-full bg-white border rounded-lg shadow-lg;
}

.spire-toast--success {
    @apply border-green-200 bg-green-50;
}

.spire-toast--error {
    @apply border-red-200 bg-red-50;
}

.spire-toast--warning {
    @apply border-yellow-200 bg-yellow-50;
}

.spire-toast--info {
    @apply border-blue-200 bg-blue-50;
}

.spire-toast__message {
    @apply px-4 py-3 text-sm;
}

.spire-toast__close {
    @apply absolute top-2 right-2 p-1 rounded-full hover:bg-black hover:bg-opacity-10;
}
```

### Toast Customizado

```blade
<x-spire::toast class="custom-toast" message="Toast customizado" />
```

## API de Eventos

| Evento | Descrição | Payload |
|--------|-----------|---------|
| `show` | Toast exibido | - |
| `hide` | Toast ocultado | - |
| `close` | Toast fechado manualmente | - |

## Performance

- **Queue Management**: Gerenciamento de fila
- **Memory Cleanup**: Limpeza automática
- **Animation**: Transições suaves
- **Positioning**: Cálculo eficiente

## Testes

**Cobertura**: 10 testes automatizados
- Renderização e tipos
- Posicionamento
- Interações do usuário
- Estados e propriedades
- Acessibilidade

## Relacionados

- [Modal](modal.md) - Para conteúdo maior
- [Alert](alert.md) - Para mensagens persistentes
- [Button](button.md) - Para ações nos toasts
- [Icon](icon.md) - Para ícones nos toasts