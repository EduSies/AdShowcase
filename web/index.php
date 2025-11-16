<?php

use yii\helpers\ArrayHelper;
use yii\web\HeadersAlreadySentException;

try {
    require dirname(__DIR__) . '/vendor/autoload.php';
    require_once dirname(__DIR__) . '/config/dotenv.php';

    defined('ADSHOWCASE_BASE_PATH') or define('ADSHOWCASE_BASE_PATH', ArrayHelper::getValue($_ENV, 'ADSHOWCASE_BASE_PATH', dirname(__DIR__)));
    defined('YII_ENV') or define('YII_ENV', ArrayHelper::getValue($_ENV, 'ADSHOWCASE_ENV'));
    defined('YII_DEBUG') or define('YII_DEBUG', YII_ENV == 'dev');

    require ADSHOWCASE_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
    require ADSHOWCASE_BASE_PATH . '/config/bootstrap.php';

    $configApp = ArrayHelper::merge(
        require ADSHOWCASE_BASE_PATH . '/config/main.php',
        require ADSHOWCASE_BASE_PATH . '/config/main-' . YII_ENV . '.php'
    );

    (new yii\web\Application($configApp))->run();
} catch (HeadersAlreadySentException $exception) {
} catch (Throwable $exception) {
    throw $exception;
}