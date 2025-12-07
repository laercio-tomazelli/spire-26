# AI Guidelines - SPIRE 26

> Diretrizes para assistentes de IA (GitHub Copilot, Claude, ChatGPT, etc.)

## Sobre o Projeto

**SPIRE** (Sistema de Pós-Venda Integrado para Redes de Assistência) é um ERP para gestão de pós-venda de eletrodomésticos e eletrônicos, conectando fabricantes, assistências técnicas autorizadas e consumidores.

### Entidades Principais

-   **Tenant** - Empresa/organização (multi-tenant)
-   **Manufacturer** - Fabricante (Samsung, LG, Electrolux, etc.)
-   **Brand** - Marca do fabricante
-   **ProductLine** - Linha de produtos (ex: Refrigeradores, Lavadoras)
-   **ProductCategory** - Categoria (ex: Frost Free, Top Load)
-   **ProductModel** - Modelo específico do produto
-   **Part** - Peça de reposição
-   **Partner** - Assistência técnica autorizada
-   **Customer** - Consumidor final
-   **ServiceOrder** - Ordem de serviço (core do sistema)
-   **Order** - Pedido de peças
-   **Exchange** - Troca de produto
-   **Invoice** - Nota fiscal
-   **MonthlyClosing** - Fechamento mensal de pagamentos
-   **Shipment** - Envio/remessa de peças

### Tipos de Usuário

```php
enum UserType: string {
    case Spire = 'spire';           // Administrador do sistema
    case Partner = 'partner';        // Técnico/funcionário de assistência
    case Manufacturer = 'manufacturer'; // Funcionário do fabricante
    case Client = 'client';          // Consumidor final
}
```

### Arquitetura Multi-Tenant

Todos os models que pertencem a um tenant usam o trait `BelongsToTenant`:

```php
use App\Models\Concerns\BelongsToTenant;

class ServiceOrder extends Model
{
    use BelongsToTenant;
}
```

O trait adiciona automaticamente:

-   Global scope para filtrar por tenant
-   Auto-preenchimento do `tenant_id` ao criar

## Stack Técnica

-   **PHP** 8.4+ (com `declare(strict_types=1)`)
-   **Laravel** 12
-   **MariaDB** 11.4+
-   **Pest** 4 (testes)
-   **PHPStan** level 5
-   **Pint** (code style Laravel)
-   **Rector** (refactoring)
-   **Tailwind CSS** 4
-   **spire-ui** (biblioteca de componentes TypeScript/Blade)

## Frontend - spire-ui

O projeto utiliza a biblioteca **spire-ui** (`laercio-tomazelli/spire-ui`) para todos os componentes de interface.

### Diretrizes Obrigatórias

1. **Sempre usar componentes spire-ui** - Verificar primeiro se existe um componente disponível
2. **TypeScript** - Preferir TypeScript sobre JavaScript puro
3. **Tailwind CSS** - Usar classes Tailwind para estilos, evitar CSS inline ou classes CSS comuns
4. **Contribuir com spire-ui** - Se um componente não existir ou precisar de alteração:
    - Criar/modificar no repositório `spire-ui-package`
    - Manter compatibilidade com versões anteriores
    - Seguir as convenções do projeto spire-ui
    - Fazer push e atualizar o package no projeto principal

### Componentes Blade Disponíveis

Usar com prefixo `<x-ui.nome>`:

```blade
{{-- Exemplos de uso --}}
<x-ui.button variant="primary">Salvar</x-ui.button>
<x-ui.modal id="confirm-modal" title="Confirmar">...</x-ui.modal>
<x-ui.input name="email" type="email" label="E-mail" />
<x-ui.select name="status" :options="$statuses" />
<x-ui.dropdown>...</x-ui.dropdown>
<x-ui.card>...</x-ui.card>
<x-ui.alert type="success">Salvo com sucesso!</x-ui.alert>
<x-ui.tabs>...</x-ui.tabs>
<x-ui.accordion>...</x-ui.accordion>
```

**Componentes disponíveis:** accordion, alert, avatar, badge, banner, breadcrumbs, button, card, carousel, clipboard, collapse, combobox, context-menu, date-range-picker, drawer, dropdown, empty-state, header, icon, input, mini-chart, modal, multiselect, notification-bell, pin, popover, pricing-card, progress, rating, select, skeleton, stats-card, stepper, swap, tabs, tag-input, timeline, tooltip

### Componentes TypeScript

Localização: `resources/js/spire/`

```typescript
// Importar componentes
import { Modal, Dropdown, Tabs } from "@/spire/components";
import { Toast, Http, Mask, Currency } from "@/spire/utilities";

// Uso
Modal.open("my-modal");
Toast.success("Operação realizada!");
const formatted = Currency.format(1234.56, "BRL");
```

**Componentes:** Accordion, Button, Carousel, Clipboard, Collapse, ColorPicker, CommandPalette, ContextMenu, DatePicker, DateRangePicker, Drawer, Dropdown, FileUpload, FormValidator, InfiniteScroll, Input, LazyLoad, Modal, MultiSelect, Persist, Popover, Progress, RangeSlider, Rating, Select, Skeleton, Stepper, Table, Tabs, Tooltip, VirtualScroll

**Utilitários:** A11y, Confirm, Currency, EventBus, Http, Mask, Perf, Shortcuts, Toast

### Quando Criar/Modificar spire-ui

Se precisar de um componente que não existe ou modificar um existente:

```bash
# 1. Ir para o repositório spire-ui-package
cd /home/ldo/dev/projects/spire-ui-package

# 2. Fazer as alterações necessárias
# - Componentes Blade: resources/views/components/ui/
# - Componentes TS: resources/js/spire/components/
# - Utilitários TS: resources/js/spire/utilities/

# 3. Commit e push
git add .
git commit -m "feat: add NewComponent"
git push

# 4. Voltar ao projeto e atualizar
cd /home/ldo/dev/projects/spire-26
composer update laercio-tomazelli/spire-ui
php artisan vendor:publish --provider="LaercioTomazelli\SpireUI\SpireUIServiceProvider" --tag=spire-ui-assets --force
php artisan vendor:publish --provider="LaercioTomazelli\SpireUI\SpireUIServiceProvider" --tag=spire-ui-views --force
```

### O que NÃO fazer no Frontend

-   ❌ Não usar JavaScript puro quando TypeScript é possível
-   ❌ Não criar componentes diretamente no projeto (criar no spire-ui)
-   ❌ Não usar estilos inline (`style="..."`)
-   ❌ Não criar classes CSS comuns (usar Tailwind)
-   ❌ Não usar jQuery ou bibliotecas externas sem necessidade
-   ❌ Não duplicar funcionalidade que já existe no spire-ui

## Comandos

```bash
composer dev          # Servidor + queue + pail + vite
composer test         # Rodar testes (Pest paralelo)
composer lint         # Corrigir estilo (Pint + Rector)
composer analyse      # Análise estática (PHPStan)
composer check        # Rodar tudo (lint + analyse + test)

php artisan migrate:fresh --seed  # Recriar banco com dados de exemplo
php artisan ide-helper:models --write  # Atualizar PHPDocs dos Models
```

## Convenções PHP

### Obrigatório

-   `declare(strict_types=1)` em todo arquivo PHP
-   Type hints em parâmetros e retornos
-   Constructor property promotion
-   Early return pattern
-   Usar `Auth` facade em vez de `auth()` helper (melhor para IDE)

### Exemplo de Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceOrder extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'partner_id',
        'customer_id',
        'status_id',
        // ...
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_warranty' => 'boolean',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(ServiceOrderPart::class);
    }
}
```

### Exemplo de Service

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ServiceOrder;

final readonly class ServiceOrderService
{
    public function __construct(
        private ServiceOrderRepository $repository,
    ) {}

    public function findActive(int $id): ?ServiceOrder
    {
        $order = $this->repository->find($id);

        if (! $order?->isActive()) {
            return null;
        }

        return $order;
    }
}
```

## Estrutura Laravel 12

-   **Sem** `app/Http/Kernel.php` - use `bootstrap/app.php`
-   **Sem** `app/Console/Kernel.php` - comandos auto-registram
-   Middleware registrado em `bootstrap/app.php`
-   Providers em `bootstrap/providers.php`

## Database

-   Usar Eloquent, evitar `DB::` facade
-   Eager loading para evitar N+1
-   Migrations com return type `: void`
-   Factories para todos os models
-   Usar `decimal(15, 4)` para valores monetários
-   Usar `comment()` nas foreign keys para documentar

### Padrão de Migrations

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->comment('Tenant owner');
            $table->foreignId('partner_id')->constrained()->comment('Assigned partner');
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
```

## Testes (Pest)

```php
<?php

declare(strict_types=1);

use App\Models\ServiceOrder;
use App\Models\Partner;

it('creates a service order for a partner', function (): void {
    $partner = Partner::factory()->create();

    $order = ServiceOrder::factory()
        ->for($partner)
        ->create();

    expect($order)
        ->toBeInstanceOf(ServiceOrder::class)
        ->partner_id->toBe($partner->id);
});
```

## O que NÃO fazer

### Backend

-   ❌ Não usar `env()` fora de config/
-   ❌ Não criar classes sem type hints
-   ❌ Não usar `==`, sempre `===`
-   ❌ Não criar arquivos de documentação sem pedir
-   ❌ Não remover testes existentes
-   ❌ Não usar `auth()` helper, usar `Auth` facade
-   ❌ Não esquecer de rodar `composer check` antes de finalizar

### Frontend

-   ❌ Não usar JavaScript puro quando TypeScript é possível
-   ❌ Não criar componentes UI diretamente no projeto (criar no spire-ui-package)
-   ❌ Não usar estilos inline (`style="..."`)
-   ❌ Não criar classes CSS comuns (usar Tailwind)
-   ❌ Não usar jQuery ou bibliotecas externas sem necessidade
-   ❌ Não duplicar funcionalidade que já existe no spire-ui

## Arquivos Importantes

-   `docs/data-migration-mapping.md` - Mapeamento do banco legado para o novo
-   `database/seeders/` - Seeders com dados de exemplo realistas
-   `app/Models/Concerns/BelongsToTenant.php` - Trait de multi-tenancy
-   `resources/js/spire/` - Componentes e utilitários TypeScript do spire-ui
-   `resources/views/vendor/spire-ui/` - Componentes Blade do spire-ui
-   `/home/ldo/dev/projects/spire-ui-package/` - Repositório fonte do spire-ui

## Verificação

Antes de finalizar qualquer tarefa, sempre rodar:

```bash
composer check
```

Isso executa: Pint → Rector → PHPStan → Pest
