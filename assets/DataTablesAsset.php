<?php

namespace app\assets;

use nullref\datatable\assets\DataTableAsset;
use yii\web\AssetBundle;

class DataTablesAsset extends AssetBundle
{
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.3/css/bulma.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        'https://cdn.datatables.net/2.3.5/css/dataTables.bulma.css',
    ];

    public $js = [
        'https://cdn.datatables.net/2.3.5/js/dataTables.bulma.js',
    ];

    public $depends = [
        DataTableAsset::class,
    ];
}