<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * AssetBundle para cargar los estilos de 'flag-icon-css'.
 *
 * NOTA: Se utiliza este Asset personalizado en lugar del incluido en la extensión
 * original para evitar la carga de dependencias de Bootstrap 3 o conflictos de versiones.
 * Carga directamente la librería CSS necesaria para que el widget Flag funcione.
 */
class FlagAsset extends AssetBundle
{
    public $css = [
        'https://cdn.jsdelivr.net/npm/flag-icon-css@4.1.7/css/flag-icons.min.css',
    ];
}