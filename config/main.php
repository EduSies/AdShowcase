<?php

$params = require ADSHOWCASE_BASE_PATH . '/config/params.php';
$db = require ADSHOWCASE_BASE_PATH . '/config/db.php';

$name_app = \yii\helpers\ArrayHelper::getValue($_ENV, 'APP_NAME');

$routes = file_exists(ADSHOWCASE_BASE_PATH . '/config/routes.php') ? require ADSHOWCASE_BASE_PATH . '/config/routes.php' : null;

$config = [
    'id' => $name_app,
    'name' => $name_app,
    'basePath' => ADSHOWCASE_BASE_PATH,
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'sh-PCssYcqfA2qlYqUxVhIPhnlnVG4QW',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => ['@adshowcase/migrations'],
        ],
    ],
    'as accessBackoffice' => [
        'class' => \yii\filters\AccessControl::class,
        'ruleConfig' => ['class' => \yii\filters\AccessRule::class],
        'only' => ['backoffice/*'],
        'denyCallback' => function() { throw new \yii\web\ForbiddenHttpException('No tienes permisos de acceso.'); },
        'rules' => [
            ['allow' => true, 'roles' => ['admin', 'editor', 'sales']], // viewer, guest fuera
        ],
    ],
    'params' => $params,
    'vendorPath' => ADSHOWCASE_BASE_PATH . '/vendor',
];

return $config;