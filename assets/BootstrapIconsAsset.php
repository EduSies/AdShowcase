<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapIconsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/twbs/bootstrap-icons';

    public $css = [
        'font/bootstrap-icons.min.css',
    ];

    public $depends = [
        \yii\web\JqueryAsset::class,
        \yii\bootstrap5\BootstrapAsset::class,
    ];
}