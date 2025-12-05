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

    // --- Backoffice (module/prefix) ---
    'back-office' => 'back-office/index',
    'back-office/<action:[a-z0-9\-]+>/<hash:[A-Za-z0-9_\-]{16}>' => 'back-office/<action>',
    'back-office/<action:[a-z0-9\-]+>' => 'back-office/<action>',

    // --- Catalog shortcuts (slugs and IDs) ---
/*    'brands/<slug:[a-z0-9\-]+>' => 'brand/view',
    'creative/<id:\d+>' => 'creative/view',*/
    'c/<hash:[A-Za-z0-9]{10}>' => 'creative/view-by-hash', // short link by hash

    // --- Shared links / Favorite lists ---
    's/<token:[A-Za-z0-9_\-]{43}>' => 'shared-link/open', // 43-char base64url token
    'favorites' => 'favorite/index',
    'favorites/<hash:[A-Za-z0-9]{16}>' => 'favorite/view-list',
];

return [
    'class' => UrlManager::class,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => $rules,
];