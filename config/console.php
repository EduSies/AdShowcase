<?php

use yii\helpers\ArrayHelper;

$params = require ADSHOWCASE_BASE_PATH . '/config/params.php';
$db = require ADSHOWCASE_BASE_PATH . '/config/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => ADSHOWCASE_BASE_PATH,
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'useFileTransport' => false,
            'transport' => [
                'scheme' => 'smtp',
                'host' => ArrayHelper::getValue($_ENV, 'SMTP_HOST'),
                'username' => ArrayHelper::getValue($_ENV, 'SMTP_USER'),
                'password' => ArrayHelper::getValue($_ENV, 'SMTP_PASS'),
                'port' => (int) ArrayHelper::getValue($_ENV, 'SMTP_PORT'),
                'encryption' => ArrayHelper::getValue($_ENV, 'SMTP_ENCRYPTION'),
            ],
            'messageConfig' => [
                'from' => [ArrayHelper::getValue($_ENV, 'SMTP_USER') => ArrayHelper::getValue($_ENV, 'APP_NAME')],
            ],
        ],
        'authManager' => [
            'class' => \yii\rbac\DbManager::class,
            'cache' => 'cache',
            'defaultRoles' => ['guest'],
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
    'params' => $params,
    'vendorPath' => ADSHOWCASE_BASE_PATH . '/vendor',
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
}

return $config;