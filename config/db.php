<?php

use yii\db\Connection;
use yii\helpers\ArrayHelper;

return [
    'class' => Connection::class,
    'dsn' => 'mysql:host='
        . ArrayHelper::getValue($_ENV, 'ADSHOWCASE_DB_HOST') .
        ';dbname=' . ArrayHelper::getValue($_ENV, 'ADSHOWCASE_DB_NAME') .
        ';port=' . ArrayHelper::getValue($_ENV, 'ADSHOWCASE_DB_PORT'),
    'username' => ArrayHelper::getValue($_ENV, 'ADSHOWCASE_DB_USER'),
    'password' => ArrayHelper::getValue($_ENV, 'ADSHOWCASE_DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'tablePrefix' => 'ADSHOWCASE_',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
