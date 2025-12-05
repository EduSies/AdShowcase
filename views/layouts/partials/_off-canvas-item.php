<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\Icon;

/** @var string $label */
/** @var string|null $icon */
/** @var string|array $url */
/** @var bool $isActive */

$classes = [
    'list-group-item',
    'list-group-item-action',
    'd-flex',
    'align-items-center',
    'gap-2',
];

if ($isActive) {
    $classes[] = 'active';
}

echo Html::a(
    trim(
        ($icon
            ? Icon::widget([
                'icon' => $icon,
                'size' => Icon::SIZE_24,
                'options' => [
                    'class' => 'flex-shrink-0',
                ],
            ])
            : ''
        ) .
        Html::tag('span', Html::encode(Yii::t('app', $label)), ['class' => 'flex-grow-1'])
    ),
    Url::to($url),
    [
        'class' => implode(' ', $classes),
    ]
);