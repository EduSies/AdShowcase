<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAssetSiteMockup extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site_mockup/mockup.css',
    ];

    public $js = [
        'js/site_mockup/mockup.js',
    ];

    public $depends = [
        AppAsset::class,
    ];
}