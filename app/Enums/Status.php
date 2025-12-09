<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Status genérico para entidades do sistema SPIRE.
 *
 * Usado para estilização visual consistente de badges e selects de status.
 */
enum Status: string
{
    case Active = '1';
    case Inactive = '0';

    /**
     * Create from boolean value.
     */
    public static function fromBool(bool $value): self
    {
        return $value ? self::Active : self::Inactive;
    }

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Active => 'Ativo',
            self::Inactive => 'Inativo',
        };
    }

    /**
     * Get badge variant for this status.
     */
    public function badgeVariant(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'danger',
        };
    }

    /**
     * Get text/icon color class for this status.
     */
    public function textColor(): string
    {
        return match ($this) {
            self::Active => 'text-green-700 dark:text-green-300',
            self::Inactive => 'text-red-700 dark:text-red-300',
        };
    }

    /**
     * Get SVG icon for this status.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Active => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            self::Inactive => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        };
    }

    /**
     * Get select options with icons and colors for all statuses.
     *
     * @return array<int, array{value: string, label: string, icon: string, color: string}>
     */
    public static function selectOptions(): array
    {
        return array_map(fn (self $status): array => [
            'value' => $status->value,
            'label' => $status->label(),
            'icon' => $status->icon(),
            'color' => $status->textColor(),
        ], self::cases());
    }

    /**
     * Get a single select option array for this status.
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
