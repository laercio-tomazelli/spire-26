<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Tipos de entidades do sistema SPIRE.
 *
 * Usado para estilização visual consistente de badges e identificadores.
 */
enum EntityType: string
{
    case Tenant = 'tenant';
    case Manufacturer = 'manufacturer';
    case Partner = 'partner';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Tenant => 'Tenant',
            self::Manufacturer => 'Fabricante',
            self::Partner => 'Posto Autorizado',
        };
    }

    /**
     * Get badge variant for this entity type.
     */
    public function badgeVariant(): string
    {
        return match ($this) {
            self::Tenant => 'info',
            self::Manufacturer => 'warning',
            self::Partner => 'success',
        };
    }

    /**
     * Get background color class for this entity type.
     */
    public function bgColor(): string
    {
        return match ($this) {
            self::Tenant => 'bg-cyan-100 dark:bg-cyan-900/50',
            self::Manufacturer => 'bg-yellow-100 dark:bg-yellow-900/50',
            self::Partner => 'bg-green-100 dark:bg-green-900/50',
        };
    }

    /**
     * Get text/icon color class for this entity type.
     */
    public function textColor(): string
    {
        return match ($this) {
            self::Tenant => 'text-cyan-700 dark:text-cyan-300',
            self::Manufacturer => 'text-yellow-700 dark:text-yellow-300',
            self::Partner => 'text-green-700 dark:text-green-300',
        };
    }

    /**
     * Get SVG icon for this entity type.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Tenant => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
            self::Manufacturer => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>',
            self::Partner => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        };
    }

    /**
     * Get select options with icons and colors for all entity types.
     *
     * @return array<int, array{value: string, label: string, icon: string, color: string}>
     */
    public static function selectOptions(): array
    {
        return array_map(fn (self $type): array => [
            'value' => $type->value,
            'label' => $type->label(),
            'icon' => $type->icon(),
            'color' => $type->textColor(),
        ], self::cases());
    }

    /**
     * Get a single select option array for this entity type.
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
