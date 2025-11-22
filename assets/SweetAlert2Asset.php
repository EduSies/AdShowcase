<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Datatables asset bundle.
 */
class SweetAlert2Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/sweetalert2/sweetalert2.css',
        'css/sweetalert2/bootstrap-5.css',
    ];

    public $js = [
        'js/sweetalert2/sweetalert2.min.js',
        'js/sweetalert2/custom-sweetalert2.js',
    ];

    public $depends = [
        \yii\web\JqueryAsset::class,
    ];
}