<?php

use yii\helpers\ArrayHelper;

Yii::setAlias('@adshowcase', ADSHOWCASE_BASE_PATH);
Yii::setAlias('@uploads', ADSHOWCASE_BASE_PATH . '/uploads');

try {

    if (empty($domain_adshowcase = ArrayHelper::getValue($_ENV, 'ADSHOWCASE_DOMAIN'))) {
        throw new Exception('Empty ADSHOWCASE_DOMAIN');
    }

    Yii::setAlias('@domain.adshowcase', $domain_adshowcase);

} catch (Exception $exception) {
    dd($exception);
}