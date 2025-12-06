<?php

namespace app\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use app\widgets\Flag;

class LangHelper
{
    /**
     * Devuelve la lista de idiomas formateada para el Nav Widget
     */
    public static function getLanguageItems(): array
    {
        return [
            [
                'label' => Flag::widget([
                        'tag' => 'span',
                        'country' => 'es',
                        'options' => ['class' => 'me-2']
                    ]) . Yii::t('app', 'Spanish'),
                'url' => ['/change-language/' . ArrayHelper::getValue($_ENV, 'LANGUAGE_ES')],
                'active' => Yii::$app->language === ArrayHelper::getValue($_ENV, 'LANGUAGE_ES'),
                'encode' => false,
            ],
            [
                'label' => Flag::widget([
                        'tag' => 'span',
                        'country' => 'es-ct',
                        'options' => ['class' => 'me-2']
                    ]) . Yii::t('app', 'Catalan'),
                'url' => ['/change-language/' . ArrayHelper::getValue($_ENV, 'LANGUAGE_CA')],
                'active' => Yii::$app->language === ArrayHelper::getValue($_ENV, 'LANGUAGE_CA'),
                'encode' => false,
            ],
            [
                'label' => Flag::widget([
                        'tag' => 'span',
                        'country' => 'gb',
                        'options' => ['class' => 'me-2']
                    ]) . Yii::t('app', 'English'),
                'url' => ['/change-language/' . ArrayHelper::getValue($_ENV, 'LANGUAGE_EN')],
                'active' => Yii::$app->language === ArrayHelper::getValue($_ENV, 'LANGUAGE_EN'),
                'encode' => false,
            ],
        ];
    }
}