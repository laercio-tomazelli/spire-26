# AI Guidelines - spire-26

> Diretrizes para assistentes de IA (GitHub Copilot, Claude, ChatGPT, etc.)

## Stack

- **PHP** 8.4+ (com `declare(strict_types=1)`)
- **Laravel** 12
- **Pest** 4 (testes)
- **PHPStan** level 6
- **Pint** (code style)
- **Rector** (refactoring)
- **Tailwind CSS** 4

## Comandos

```bash
composer dev          # Servidor + queue + pail + vite
composer test         # Rodar testes (Pest paralelo)
composer lint         # Corrigir estilo (Pint + Rector)
composer analyse      # Análise estática (PHPStan)
composer check        # Rodar tudo (lint + analyse + test)
```

## Convenções PHP

### Obrigatório
- `declare(strict_types=1)` em todo arquivo PHP
- Type hints em parâmetros e retornos
- Constructor property promotion
- Early return pattern

### Exemplo
```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

final class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {}

    public function findActive(int $id): ?User
    {
        $user = $this->repository->find($id);

        if (! $user?->is_active) {
            return null;
        }

        return $user;
    }
}
```

## Estrutura Laravel 12

- **Sem** `app/Http/Kernel.php` - use `bootstrap/app.php`
- **Sem** `app/Console/Kernel.php` - comandos auto-registram
- Middleware registrado em `bootstrap/app.php`
- Providers em `bootstrap/providers.php`

## Database

- Usar Eloquent, evitar `DB::` facade
- Eager loading para evitar N+1
- Migrations com return type `: void`
- Factories para todos os models

## Testes (Pest)

```php
<?php

declare(strict_types=1);

use App\Models\User;

it('creates a user', function (): void {
    $user = User::factory()->create();

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->id)->toBeInt();
});
```

## O que NÃO fazer

- ❌ Não usar `env()` fora de config/
- ❌ Não criar classes sem type hints
- ❌ Não usar `==`, sempre `===`
- ❌ Não criar arquivos de documentação sem pedir
- ❌ Não remover testes existentes

## Verificação

Antes de finalizar, sempre rodar:
```bash
composer check
```
