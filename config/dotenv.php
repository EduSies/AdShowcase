<?php

// read .env con vlucas

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

try {
    $currentEnv = ArrayHelper::getValue($_ENV, 'ADSHOWCASE_ENV');

    if (!in_array($currentEnv, ['dev', 'prod'])) {
        throw new InvalidConfigException('Invalid environment definition, please check .env file');
    }
} catch (Exception $e) {
    dd($e);
}