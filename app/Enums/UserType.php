<?php

declare(strict_types=1);

namespace App\Enums;

enum UserType: string
{
    case Spire = 'spire';
    case Partner = 'partner';
    case Manufacturer = 'manufacturer';
    case Client = 'client';

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
            self::Partner => 'Posto Autorizado',
            self::Manufacturer => 'Fabricante',
            self::Client => 'Cliente',
        };
    }

    /**
     * Check if user type can manage service orders.
     */
    public function canManageServiceOrders(): bool
    {
        return in_array($this, [self::Spire, self::Partner, self::Manufacturer], true);
    }

    /**
     * Check if user type can manage parts/orders.
     */
    public function canManageParts(): bool
    {
        return in_array($this, [self::Spire, self::Partner, self::Manufacturer], true);
    }

    /**
     * Check if user type is internal (not client).
     */
    public function isInternal(): bool
    {
        return $this !== self::Client;
    }

    /**
     * Check if this user type belongs to a specific entity.
     */
    public function requiresEntity(): bool
    {
        return $this !== self::Spire;
    }

    /**
     * Get the entity relationship name for this user type.
     */
    public function entityRelation(): ?string
    {
        return match ($this) {
            self::Partner => 'partner',
            self::Manufacturer => 'manufacturer',
            self::Client => 'customer',
            self::Spire => null,
        };
    }
}
