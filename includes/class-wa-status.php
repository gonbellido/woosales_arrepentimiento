<?php
/**
 * Workflow de estados para reclamaciones.
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Status
{
    public const PENDIENTE          = 'pendiente';
    public const EN_REVISION        = 'en_revision';
    public const APROBADA           = 'aprobada';
    public const RECHAZADA          = 'rechazada';
    public const REINTEGRO_REALIZADO = 'reintegro_realizado';

    public function __construct()
    {
        // Nada que enganchar por ahora; pura lógica de dominio.
    }

    /**
     * Labels legibles.
     */
    public static function labels(): array
    {
        return [
            self::PENDIENTE           => __('Pendiente', 'boton-de-arrepentimiento-argentina-woosales-2'),
            self::EN_REVISION         => __('En Revisión', 'boton-de-arrepentimiento-argentina-woosales-2'),
            self::APROBADA            => __('Aprobada', 'boton-de-arrepentimiento-argentina-woosales-2'),
            self::RECHAZADA           => __('Rechazada', 'boton-de-arrepentimiento-argentina-woosales-2'),
            self::REINTEGRO_REALIZADO => __('Reintegro Realizado', 'boton-de-arrepentimiento-argentina-woosales-2'),
        ];
    }

    /**
     * Label para un estado.
     */
    public static function label(string $status): string
    {
        return self::labels()[$status] ?? $status;
    }

    /**
     * Estados válidos.
     */
    public static function estados(): array
    {
        return array_keys(self::labels());
    }

    /**
     * Estado por defecto para nuevas reclamaciones.
     */
    public static function default(): string
    {
        return self::PENDIENTE;
    }

    /**
     * Transiciones permitidas.
     */
    public static function transitions(): array
    {
        return [
            self::PENDIENTE           => [self::EN_REVISION, self::RECHAZADA],
            self::EN_REVISION         => [self::APROBADA, self::RECHAZADA],
            self::APROBADA            => [self::REINTEGRO_REALIZADO],
            self::RECHAZADA           => [],
            self::REINTEGRO_REALIZADO => [],
        ];
    }

    /**
     * Valida si una transición es permitida.
     */
    public static function can_transition(string $from, string $to): bool
    {
        $allowed = self::transitions()[$from] ?? [];
        return in_array($to, $allowed, true);
    }

    /**
     * Colores por estado (para badges).
     */
    public static function color(string $status): string
    {
        $colors = [
            self::PENDIENTE           => '#f0ad4e', // amarillo
            self::EN_REVISION         => '#5bc0de', // celeste
            self::APROBADA            => '#5cb85c', // verde
            self::RECHAZADA           => '#d9534f', // rojo
            self::REINTEGRO_REALIZADO => '#777777', // gris
        ];
        return $colors[$status] ?? '#999';
    }
}
