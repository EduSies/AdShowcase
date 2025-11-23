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
        $req = Yii::$app->request;
        $cookies = $req->cookies;
        $session = Yii::$app->session;

        //dump($cookies, $session);

        // Lista blanca de idiomas soportados
        $allowed = [
            ArrayHelper::getValue($_ENV, 'LANGUAGE_ES'),
            ArrayHelper::getValue($_ENV, 'LANGUAGE_CA'),
            ArrayHelper::getValue($_ENV, 'LANGUAGE_EN'),
        ];
        // Fallback: usa el configurado por defecto en la app
        $default = Yii::$app->language;

        //dump($default);

        // 1) Sesión / cookie
        $lang = $session->get('_lang') ?? $cookies->getValue('_lang');
//dump($lang);
        // 2) Si el usuario está logueado y tiene idioma guardado, priorízalo
        if (!$lang && !Yii::$app->user->isGuest) {
            $u = Yii::$app->user->identity;
            if ($u && !empty($u->language)) {
                $lang = $u->language;
            }
        }

        // 3) Si aún no hay idioma, negociar con el navegador (Accept-Language)
        if (!$lang) {
            // Devuelve la mejor coincidencia de $allowed con el header del navegador
            $detected = Yii::$app->request->getPreferredLanguage($allowed);
            //dump($detected);
            $lang = $detected ?: $default;
        }

        // 4) Validar contra la whitelist y aplicar
        if (!in_array($lang, $allowed, true)) {
            $lang = $default;
        }

        Yii::$app->language = $lang;
    },
    'as accessBackoffice' => [
        'class' => \yii\filters\AccessControl::class,
        'ruleConfig' => ['class' => \yii\filters\AccessRule::class],
        'only' => ['backoffice/*'],
        'denyCallback' => function() {
            if (Yii::$app->user->isGuest) {
                return Yii::$app->response->redirect(['auth/login']);
            }
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
        },
        'rules' => [
            ['allow' => true, 'roles' => ['admin', 'editor', 'sales']], // viewer, guest fuera
        ],
    ],
    'params' => $params,
    'vendorPath' => ADSHOWCASE_BASE_PATH . '/vendor',
];

return $config;