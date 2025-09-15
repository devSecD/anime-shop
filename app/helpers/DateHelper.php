<?php

/* ejemplos de implementacion */
/*
<?= \App\helpers\DateHelper::formatShort($order['created_at']); ?>
<?= \App\helpers\DateHelper::formatLong($order['created_at']); ?>
<?= \App\helpers\DateHelper::formatRelative($order['created_at']); ?>
*/
/* ejemplos de implementacion */

namespace App\helpers;

class DateHelper
{
    /**
     * Convierte una fecha de MySQL en formato corto: dd/mm/yyyy hh:mm
     */
    public static function formatShort(string $dateTime): string
    {
        $date = new \DateTime($dateTime);
        return $date->format('d/m/Y H:i');
    }

    /**
     * Convierte una fecha de MySQL en formato largo: 11 de septiembre de 2025, 17:40 hrs
     */
    public static function formatLong(string $dateTime): string
    {
        $date = new \DateTime($dateTime);

        $formatter = new \IntlDateFormatter(
            'es_ES',                       // Idioma y región
            \IntlDateFormatter::LONG,      // Fecha larga
            \IntlDateFormatter::SHORT,     // Hora corta
            $date->getTimezone(),          // Respeta la zona horaria del objeto DateTime
            \IntlDateFormatter::GREGORIAN  // Calendario gregoriano
        );

        // Devuelve algo como: "11 de septiembre de 2025, 17:40"
        return $formatter->format($date);
    }

    /**
     * Convierte una fecha de MySQL en formato relativo: "hace 2 horas", "ayer", "hace 3 días"
     */
    public static function formatRelative(string $dateTime): string
    {
        $now = new \DateTime();
        $date = new \DateTime($dateTime);
        $diff = $now->diff($date);

        if ($diff->y > 0) {
            return $diff->y === 1 ? 'hace 1 año' : "hace {$diff->y} años";
        }
        if ($diff->m > 0) {
            return $diff->m === 1 ? 'hace 1 mes' : "hace {$diff->m} meses";
        }
        if ($diff->d > 0) {
            if ($diff->d === 1) return 'ayer';
            return "hace {$diff->d} días";
        }
        if ($diff->h > 0) {
            return $diff->h === 1 ? 'hace 1 hora' : "hace {$diff->h} horas";
        }
        if ($diff->i > 0) {
            return $diff->i === 1 ? 'hace 1 minuto' : "hace {$diff->i} minutos";
        }
        return 'justo ahora';
    }
}
