<?php

use yii\helpers\ArrayHelper;

$params = require ADSHOWCASE_BASE_PATH . '/config/params.php';
$db = require ADSHOWCASE_BASE_PATH . '/config/db.php';

$name_id = ArrayHelper::getValue($_ENV, 'APP_ID');
$name_app = ArrayHelper::getValue($_ENV, 'APP_NAME');

$routes = file_exists(ADSHOWCASE_BASE_PATH . '/config/routes.php') ? require ADSHOWCASE_BASE_PATH . '/config/routes.php' : null;

$config = [
    'id' => $name_id,
    'name' => $name_app,
    'basePath' => ADSHOWCASE_BASE_PATH,
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => ArrayHelper::getValue($_ENV, 'LANGUAGE'),
    'sourceLanguage' => ArrayHelper::getValue($_ENV, 'SOURCE_LANGUAGE'),
    'components' => [
        'request' => [
            'cookieValidationKey' => ArrayHelper::getValue($_ENV, 'COOKIE_VALIDATION_KEY'),
            'enableCsrfValidation' => true,
            'csrfParam' => ArrayHelper::getValue($_ENV, 'CSRF_SESSION_ADSHOWCASE'),
            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => $params['isSsl'],
                'sameSite' => \yii\web\Cookie::SAME_SITE_LAX,
                'domain' => ArrayHelper::getValue($_ENV, 'ADSHOWCASE_COOKIE_SESSION_DOMAIN'),
            ],
        ],
        'session' => [
            'class' => yii\web\Session::class,
            'name' => ArrayHelper::getValue($_ENV, 'ADSHOWCASE_COOKIE_SESSION_NAME'),
            'cookieParams' => [
                'httpOnly' => true,
                'secure' => $params['isSsl'],
                'sameSite' => \yii\web\Cookie::SAME_SITE_LAX,
                'domain' => ArrayHelper::getValue($_ENV, 'ADSHOWCASE_COOKIE_SESSION_DOMAIN'),
                'lifetime' => 3600 * 24 * 7, // vida de la cookie de sesión (navegador)
            ],
            'timeout' => 3600 * 24 * 1, // vida de la sesión en servidor (inactividad)
        ],
        'user' => [
            'identityClass' => app\models\User::class,
            'enableAutoLogin' => true, // necesario para rememberMe
            'loginUrl' => 'auth/login',
            'identityCookie' => [
                'name' => ArrayHelper::getValue($_ENV, 'IDENTITY_COOKIE_ADSHOWCASE'),
                'httpOnly' => true,
                'secure' => $params['isSsl'],
                'sameSite' => \yii\web\Cookie::SAME_SITE_LAX,
                'domain' => ArrayHelper::getValue($_ENV, 'ADSHOWCASE_COOKIE_SESSION_DOMAIN'),
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'base-web/error'
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                \nullref\datatable\assets\DataTableDefaultAsset::class => [
                    'sourcePath' => null,
                ],
            ],
        ],
/*        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],*/
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'useFileTransport' => false,
            'transport' => [
                'scheme' => 'smtp',
                'host' => ArrayHelper::getValue($_ENV, 'MAILTRAP_HOST'),
                'username' => ArrayHelper::getValue($_ENV, 'MAILTRAP_USER'),
                'password' => ArrayHelper::getValue($_ENV, 'MAILTRAP_PASS'),
                'port' => (int) ArrayHelper::getValue($_ENV, 'MAILTRAP_PORT'),
                'encryption' => 'tls',
            ],
/*            'messageConfig' => [
                'from' => ['noreply@tu-dominio-verificado.com' => 'AdShowcase'],
            ],*/
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => $routes,
        'authManager' => [
            'class' => \yii\rbac\DbManager::class,
            'cache' => 'cache',
            'defaultRoles' => ['guest'], // rol por defecto para no autenticados
            'itemTable' => '{{%auth_item}}',
            'itemChildTable' => '{{%auth_item_child}}',
            'assignmentTable' => '{{%auth_assignment}}',
            'ruleTable' => '{{%auth_rule}}',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@adshowcase/messages',
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => ['@adshowcase/migrations'],
        ],
    ],
    'on beforeRequest' => function () {
        $session = Yii::$app->session;

        // Lista blanca de idiomas soportados
        $allowed = [
            ArrayHelper::getValue($_ENV, 'LANGUAGE_ES'),
            ArrayHelper::getValue($_ENV, 'LANGUAGE_CA'),
            ArrayHelper::getValue($_ENV, 'LANGUAGE_EN'),
        ];
        // Idioma por defecto de la aplicación
        $default = Yii::$app->language;

        // 1) Intentar leer SOLO de la SESIÓN
        $lang = $session->get('_lang');

        // 2) Si no hay nada en sesión, mirar si el usuario tiene preferencia guardada en BD
        if (!$lang && !Yii::$app->user->isGuest) {
            $u = Yii::$app->user->identity;
            if ($u && !empty($u->language)) {
                $lang = $u->language;
            }
        }

        // 3) Si sigue sin haber idioma, detectar del navegador
        if (!$lang) {
            $lang = Yii::$app->request->getPreferredLanguage($allowed);
        }

        // 4) Validar contra la whitelist (fallback al default si no es válido)
        if (!$lang || !in_array($lang, $allowed, true)) {
            $lang = $default;
        }

        // 5) Aplicar el idioma
        Yii::$app->language = $lang;

        // 6) GUARDAR EN SESIÓN (si ha cambiado o no existía)
        // Esto asegura que en la siguiente petición (F5) se recuerde lo elegido/detectado
        if ($session->get('_lang') !== $lang) {
            $session->set('_lang', $lang);
        }
    },
    'params' => $params,
    'vendorPath' => ADSHOWCASE_BASE_PATH . '/vendor',
];

return $config;