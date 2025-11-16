<?php

use yii\helpers\ArrayHelper;
use yii\web\UrlManager;

// Custom routes adshowcase
$rules = [

];

$url_domain = ArrayHelper::getValue($_ENV, 'ADSHOWCASE_DOMAIN');

return [
    'class' => UrlManager::class,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'scriptUrl' => $url_domain,
    'baseUrl' => $url_domain,
    'hostInfo' => $url_domain,
    'rules' => $rules,
];