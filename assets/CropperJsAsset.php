<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for CropperJS integration.
 */
class CropperJsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        // Librería Core
        'https://cdn.jsdelivr.net/npm/cropperjs@2.1.0/dist/cropper.min.js',
        // Tu script personalizado
        'js/cropper/custom-cropper.js',
    ];

    public $depends = [
        \yii\web\JqueryAsset::class,
        \yii\bootstrap5\BootstrapAsset::class,
    ];
}