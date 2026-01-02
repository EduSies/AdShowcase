<?php

declare(strict_types=1);

namespace app\helpers;

use Yii;

class SharedLinkHelper
{
    /**
     * Devuelve las opciones de TTL disponibles para usar en validación y en la Vista (Select).
     */
    public static function getTtlOptions(): array
    {
        return [
            '-1'  => Yii::t('app', 'Never'),
            '24h' => Yii::t('app', '24 Hours'),
            '48h' => Yii::t('app', '48 Hours'),
            '1w'  => Yii::t('app', '1 Week'),
            '1m'  => Yii::t('app', '1 Month'),
        ];
    }

    /**
     * Convierte el string del select (ej: '24h') a horas reales (int).
     * * @param string|null $ttlKey La clave seleccionada (ej: '24h', '1w')
     * @return int Horas totales (-1 si es infinito o desconocido)
     */
    public static function getHoursFromTtl(?string $ttlKey): int
    {
        return match ($ttlKey) {
            '24h' => 24,
            '48h' => 48,
            '1w'  => 168, // 1 Semana
            '1m'  => 720, // 1 Mes
            default => -1,
        };
    }
}