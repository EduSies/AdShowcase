<?php

namespace app\assets;

use yii\web\AssetBundle;

class DataTablesAsset extends AssetBundle
{
    public $css = [
        'https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css',
    ];

    public $js = [
        'https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js',
    ];

    public $depends = [
        \yii\web\JqueryAsset::class,
        \yii\bootstrap5\BootstrapAsset::class,
        \yii\bootstrap5\BootstrapPluginAsset::class,
    ];
}