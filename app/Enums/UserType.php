<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Tipos de usuário do sistema SPIRE.
 *
 * - Spire: Equipe interna da Spire (administradores do sistema)
 * - SpireClient: Clientes da Spire (empresas que contratam o sistema = Tenants)
 * - Partner: Técnicos/funcionários de assistências técnicas autorizadas
 * - Manufacturer: Representantes de fabricantes
 *
 * Nota: Consumidores finais (atendidos nas OS) não têm usuário no sistema.
 * Quando necessário acesso, é feito via tokens temporários.
 */
enum UserType: string
{
    case Spire = 'spire';
    case SpireClient = 'spire_client';
    case Partner = 'partner';
    case Manufacturer = 'manufacturer';

    /**
     * Get all values as array.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Spire => 'Administrador Spire',
            self::SpireClient => 'Cliente Spire',
            self::Partner => 'Posto Autorizado',
            self::Manufacturer => 'Fabricante',
        };
    }

    /**
     * Get badge variant for this user type.
     */
    public function badgeVariant(): string
    {
        return match ($this) {
            self::Spire => 'primary',
            self::SpireClient => 'info',
            self::Partner => 'success',
            self::Manufacturer => 'warning',
        };
    }

    /**
     * Get background color class for this user type.
     */
    public function bgColor(): string
    {
        return match ($this) {
            self::Spire => 'bg-blue-100 dark:bg-blue-900/50',
            self::SpireClient => 'bg-cyan-100 dark:bg-cyan-900/50',
            self::Partner => 'bg-green-100 dark:bg-green-900/50',
            self::Manufacturer => 'bg-yellow-100 dark:bg-yellow-900/50',
        };
    }

    /**
     * Get text/icon color class for this user type.
     */
    public function textColor(): string
    {
        return match ($this) {
            self::Spire => 'text-blue-700 dark:text-blue-300',
            self::SpireClient => 'text-cyan-700 dark:text-cyan-300',
            self::Partner => 'text-green-700 dark:text-green-300',
            self::Manufacturer => 'text-yellow-700 dark:text-yellow-300',
        };
    }

    /**
     * Get SVG icon for this user type.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Spire => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
            self::SpireClient => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
            self::Partner => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            self::Manufacturer => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>',
        };
    }

    /**
     * Check if user type can manage service orders.
     */
    public function canManageServiceOrders(): bool
    {
        return true; // Todos os tipos podem gerenciar OS
    }

    /**
     * Check if user type can manage parts/orders.
     */
    public function canManageParts(): bool
    {
        return true; // Todos os tipos podem gerenciar peças
    }

    /**
     * Check if this user type belongs to a specific entity.
     */
    public function requiresEntity(): bool
    {
        return ! in_array($this, [self::Spire, self::SpireClient], true);
    }

    /**
     * Check if this is a Spire internal user (Spire or SpireClient).
     */
    public function isSpireUser(): bool
    {
        return in_array($this, [self::Spire, self::SpireClient], true);
    }

    /**
     * Get the entity relationship name for this user type.
     */
    public function entityRelation(): ?string
    {
        return match ($this) {
            self::Partner => 'partner',
            self::Manufacturer => 'manufacturer',
            self::Spire, self::SpireClient => null,
        };
    }

    /**
     * Get select options with icons and colors for all user types.
     *
     * @return array<int, array{value: string, label: string, icon: string, color: string}>
     */
    public static function selectOptions(): array
    {
        return array_map(fn (self $type) => [
            'value' => $type->value,
            'label' => $type->label(),
            'icon' => $type->icon(),
            'color' => $type->textColor(),
        ], self::cases());
    }

    /**
     * Get a single select option array for this user type.
     *
     * @return array{value: string, label: string, icon: string, color: string}
     */
    public function toSelectOption(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'icon' => $this->icon(),
            'color' => $this->textColor(),
        ];
    }
}
