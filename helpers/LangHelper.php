<?php

namespace app\helpers;

use Yii;
use app\widgets\Flag;
use app\models\LanguageLocale;

class LangHelper
{
    /**
     * Obtiene la configuración de idiomas desde la BD (Cacheada).
     */
    public static function getLanguagesConfig(): array
    {
        return Yii::$app->cache->getOrSet('app_languages_config', function () {

            $languages = LanguageLocale::find()
                ->where(['status' => 'active'])
                ->all();

            $config = [];

            foreach ($languages as $lang) {
                // Lógica de banderas
                $flagCode = strtolower($lang->region_code);

                if ($lang->language_code === 'ca') {
                    $flagCode = 'es-ct';
                } elseif ($lang->language_code === 'en') {
                    $flagCode = 'us';
                }

                // Usamos locale_code como clave principal para la App (URLs, detección)
                $config[$lang->locale_code] = [
                    'label' => $lang->display_name_en,
                    'flag' => $flagCode,
                    'id' => $lang->id,
                ];
            }

            return $config;
        }, 3600); // Cache de 1h
    }

    /**
     * Devuelve array [ID => Nombre] para el dropdown del formulario de usuarios.
     * CORREGIDO: Ahora las claves son INT (1, 2...) en vez de STRING ('es-ES'...).
     */
    public static function getLanguageOptions(): array
    {
        $options = [];

        foreach (self::getLanguagesConfig() as $code => $config) {
            // Usamos el ID como value del <option>
            $options[$config['id']] = Yii::t('app', $config['label']);
        }

        return $options;
    }

    /**
     * Devuelve lista para el Menú (Nav Widget).
     * Aquí seguimos usando el CÓDIGO ('es-ES') para la URL.
     */
    public static function getLanguageItems(): array
    {
        $items = [];

        foreach (self::getLanguagesConfig() as $code => $config) {
            $items[] = [
                'label' => Flag::widget([
                        'tag' => 'span',
                        'country' => $config['flag'],
                        'options' => ['class' => 'me-2']
                    ]) . Yii::t('app', $config['label']),
                'url' => ['/change-language/' . $code], // URL usa código (es-ES)
                'active' => Yii::$app->language === $code,
                'encode' => false,
            ];
        }

        return $items;
    }

    /**
     * Devuelve los códigos permitidos (es-ES, en-US...) para validación global.
     */
    public static function getAllowedLanguages(): array
    {
        return array_keys(self::getLanguagesConfig());
    }
}