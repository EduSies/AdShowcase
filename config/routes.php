<?php

use yii\web\UrlManager;

// Custom routes adshowcase
$rules = [
    // --- Home ---
    '' => 'site/index',

    // --- Auth (pretty URLs for login/registration/reset/verify) ---
    'login' => 'auth/login',
    'logout' => 'auth/logout',
    //'signup' => 'auth/signup',
    'request-password-reset' => 'auth/request-password-reset',
    'reset-password/<token:[A-Za-z0-9_\-\.]+>' => 'auth/reset-password',
    'verify-email/<token:[A-Za-z0-9_\-\.]+>' => 'auth/verify-email',

    // --- Catalog Route ---
    'catalog' => 'site/catalog',

    // --- Backoffice (module/prefix) ---
    'back-office' => 'back-office/index',
    'back-office/<action:[a-z0-9\-]+>/<hash:[A-Za-z0-9_\-]{16}>' => 'back-office/<action>',
    'back-office/<action:[a-z0-9\-]+>' => 'back-office/<action>',

    // --- Shared links / Favorite lists ---
    's/<token:[A-Za-z0-9_\-]{43}>' => 'shared-link/open', // 43-char base64url token

    // --- Favorite Route ---
    'favorites' => 'favorite/index',
    'favorites/detail/<hash:[A-Za-z0-9_\-]{16}>' => 'favorite/detail',

    // --- Rutas AJAX para manipulación de listas ---
    'favorite/get-dropdown' => 'favorite/get-dropdown',
    'favorite/create-list' => 'favorite/create-list',
    'favorite/toggle-item' => 'favorite/toggle-item',

    // --- Cambio de Idioma ---
    'change-language/<lang:[\w\-]+>' => 'language/change',
];

return [
    'class' => UrlManager::class,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => $rules,
];