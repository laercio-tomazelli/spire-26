<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Status do fluxo de Vistorias Cardif.
 *
 * Valores extraídos da planilha "Consolidado - CARDIF.xlsx" (aba Status).
 */
enum InspectionStatus: string
{
    case ToSchedule = 'agendar_vistoria';
    case Scheduled = 'vistoria_agendada';
    case AwaitingInspection = 'aguardando_vistoria';
    case AwaitingReport = 'aguardando_laudo';
    case SendReport = 'enviar_laudo';
    case Completed = 'vistoria_realizada';
    case Unsuccessful = 'insucesso';
    case NoSuccess = 'sem_sucesso';

    public function label(): string
    {
        return match ($this) {
            self::ToSchedule => 'Agendar Vistoria',
            self::Scheduled => 'Vistoria Agendada',
            self::AwaitingInspection => 'Aguardando Vistoria',
            self::AwaitingReport => 'Aguardando Laudo',
            self::SendReport => 'Enviar Laudo',
            self::Completed => 'Vistoria Realizada',
            self::Unsuccessful => 'Insucesso',
            self::NoSuccess => 'Sem Sucesso',
        };
    }

    public function badgeVariant(): string
    {
        return match ($this) {
            self::ToSchedule => 'warning',
            self::Scheduled => 'info',
            self::AwaitingInspection => 'warning',
            self::AwaitingReport => 'info',
            self::SendReport => 'primary',
            self::Completed => 'success',
            self::Unsuccessful, self::NoSuccess => 'danger',
        };
    }

    /**
     * Tailwind classes for the status summary card (inactive state).
     */
    public function cardInactiveClasses(): string
    {
        return match ($this->badgeVariant()) {
            'warning' => 'bg-amber-50 border-amber-200 text-amber-900 hover:bg-amber-100 dark:bg-amber-900/20 dark:border-amber-700/60 dark:text-amber-100 dark:hover:bg-amber-900/30',
            'info' => 'bg-sky-50 border-sky-200 text-sky-900 hover:bg-sky-100 dark:bg-sky-900/20 dark:border-sky-700/60 dark:text-sky-100 dark:hover:bg-sky-900/30',
            'primary' => 'bg-blue-50 border-blue-200 text-blue-900 hover:bg-blue-100 dark:bg-blue-900/20 dark:border-blue-700/60 dark:text-blue-100 dark:hover:bg-blue-900/30',
            'success' => 'bg-emerald-50 border-emerald-200 text-emerald-900 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-700/60 dark:text-emerald-100 dark:hover:bg-emerald-900/30',
            'danger' => 'bg-rose-50 border-rose-200 text-rose-900 hover:bg-rose-100 dark:bg-rose-900/20 dark:border-rose-700/60 dark:text-rose-100 dark:hover:bg-rose-900/30',
            default => 'bg-gray-50 border-gray-200 text-gray-900 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100 dark:hover:bg-gray-700',
        };
    }

    /**
     * Extra Tailwind classes when the card is the active filter.
     */
    public function cardActiveClasses(): string
    {
        return match ($this->badgeVariant()) {
            'warning' => 'ring-2 ring-amber-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900 bg-amber-100 dark:bg-amber-900/40',
            'info' => 'ring-2 ring-sky-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900 bg-sky-100 dark:bg-sky-900/40',
            'primary' => 'ring-2 ring-blue-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900 bg-blue-100 dark:bg-blue-900/40',
            'success' => 'ring-2 ring-emerald-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900 bg-emerald-100 dark:bg-emerald-900/40',
            'danger' => 'ring-2 ring-rose-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900 bg-rose-100 dark:bg-rose-900/40',
            default => 'ring-2 ring-gray-400 ring-offset-2 ring-offset-white dark:ring-offset-gray-900',
        };
    }

    public static function fromLabel(string $label): self
    {
        $key = mb_strtoupper(trim((string) preg_replace('/\s+/', ' ', $label)));

        return match ($key) {
            'AGENDAR VISTORIA' => self::ToSchedule,
            'VISTORIA AGENDADA' => self::Scheduled,
            'AGUARDANDO VISTORIA' => self::AwaitingInspection,
            'AGUARDANDO LAUDO' => self::AwaitingReport,
            'ENVIAR LAUDO' => self::SendReport,
            'VISTORIA REALIZADA' => self::Completed,
            'INSUCESSO' => self::Unsuccessful,
            'SEM SUCESSO' => self::NoSuccess,
            default => self::ToSchedule,
        };
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function selectOptions(): array
    {
        return array_map(
            fn (self $s): array => ['value' => $s->value, 'label' => $s->label()],
            self::cases(),
        );
    }
}
