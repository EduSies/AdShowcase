<?php

use yii\web\UrlManager;

// Custom routes adshowcase
$rules = [
    // --- Home ---
    '' => 'site/catalog',
    'dashboard' => 'site/dashboard',

    // --- Auth ---
    'login' => 'auth/login',
    'logout' => 'auth/logout',
    //'signup' => 'auth/signup',
    'request-password-reset' => 'auth/request-password-reset',
    'reset-password/<token:[A-Za-z0-9_\-\.]+>' => 'auth/reset-password',
    'verify-email/<token:[A-Za-z0-9_\-\.]+>' => 'auth/verify-email',

    // --- Catalog ---
    'catalog' => 'site/catalog',

    // --- Backoffice ---
    'back-office' => 'back-office/index',
    'back-office/<action:[a-z0-9\-]+>/<hash:[A-Za-z0-9_\-]{16}>' => 'back-office/<action>',
    'back-office/<action:[a-z0-9\-]+>' => 'back-office/<action>',

    // --- Shared links ---
    's/<token:[\w\-]+>' => 'shared-link/open',
    'shared-link/generate' => 'shared-link/generate',

    // --- Preview creative ---
    'preview/<hash:[A-Za-z0-9_\-]{16}>' => 'preview/index',
    'preview/mockup/<hash:[A-Za-z0-9_\-]{16}>' => 'preview/mockup',

    // --- Favorite Route ---
    'favorites' => 'favorite/index',
    'favorites/detail/<hash:[A-Za-z0-9_\-]{16}>' => 'favorite/detail',

    // --- Rutas AJAX para manipulación de listas ---
    'favorite/get-dropdown' => 'favorite/get-dropdown',
    'favorite/create-list' => 'favorite/create-list',
    'favorite/toggle-item' => 'favorite/toggle-item',
    'favorite/update-list' => 'favorite/update-list',
    'favorite/move-list' => 'favorite/move-list',
    'favorite/delete-list' => 'favorite/delete-list',

    // --- Cambio de Idioma ---
    'change-language/<lang:[\w\-]+>' => 'language/change',
];

return [
    'class' => UrlManager::class,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => $rules,
];