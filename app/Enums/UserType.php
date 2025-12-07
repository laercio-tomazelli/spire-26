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
}
