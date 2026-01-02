<?php

declare(strict_types=1);

namespace app\helpers;

use Yii;
use yii\helpers\Url;

class CreativeHelper
{
    /**
     * Procesa una creatividad individual añadiendo lógica de vista.
     */
    public static function prepareCreativeDisplayData(array $creative, array $listsFavorites): array
    {
        // URL Detalle
        $creative['viewDetailUrl'] = Url::to(['preview/index', 'hash' => $creative['hash']]);

        // Textos seguros
        $creative['viewFormatName'] = !empty($creative['format']) ? $creative['format']['name'] : Yii::t('app', 'Format');
        $creative['viewAgencyName'] = !empty($creative['agency']) ? $creative['agency']['name'] : Yii::t('app', 'Agency');
        $creative['viewCountryCode'] = !empty($creative['country']) ? strtolower($creative['country']['iso']) : '';
        $creative['viewCountryName'] = !empty($creative['country']) ? $creative['country']['name'] : Yii::t('app', 'Country');

        // Icono Dispositivo
        $creative['viewDeviceIcon'] = self::getDeviceIcon((int)($creative['device_id'] ?? 0));

        // Clase CSS del Dispositivo
        $creative['viewDeviceClass'] = self::getDeviceClass((int)($creative['device_id'] ?? 0));

        // Lógica de Favoritos
        $isFavorite = false;
        if (!empty($listsFavorites)) {
            foreach ($listsFavorites as $list) {
                if (isset($list['itemsHashes']) && in_array($creative['hash'], $list['itemsHashes'])) {
                    $isFavorite = true;
                    break;
                }
            }
        }

        $creative['viewIsFavorite'] = $isFavorite;
        $creative['viewFavIcon'] = $isFavorite ? 'bi-star-fill' : 'bi-star';

        // ¿Puede gestionar favoritos? (Típicamente Comercial y Admin)
        $creative['canFavorite'] = Yii::$app->user->can('favorite.manage');

        // ¿Puede compartir? (Comercial, Editor, Admin)
        $creative['canShare'] = Yii::$app->user->can('share.manage');

        return $creative;
    }

    /**
     * Devuelve el icono de Bootstrap correspondiente al ID del dispositivo.
     */
    public static function getDeviceIcon(?int $deviceId): string
    {
        return match ((int)$deviceId) {
            1 => 'bi-display', // Desktop
            2 => 'bi-phone', // Mobile
            3 => 'bi-tablet', // Tablet
            default => 'bi-display',
        };
    }

    /**
     * Devuelve la clase CSS para el iframe correspondiente al ID del dispositivo.
     */
    public static function getDeviceClass(?int $deviceId): string
    {
        return match ((int)$deviceId) {
            1 => 'device-desktop', // Desktop
            2 => 'device-mobile', // Mobile
            3 => 'device-tablet', // Tablet
            default => 'device-desktop',
        };
    }
}