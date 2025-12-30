<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use app\widgets\Icon;

/** @var string $class */
/** @var string $nameClassUrl */
/** @var string $hash */

$this->registerJsFile('@web/js/list-taxonomies-form.js', [
        'depends' => JqueryAsset::class,
]);

$editUrl = Url::to(["back-office/{$nameClassUrl}-update"]) . '/' . $hash;
$deleteUrl = Url::to(["back-office/{$nameClassUrl}-delete"]) . '/' . $hash;

?>

<div class="d-flex gap-2 justify-content-center">
    <?= Html::a(
        Icon::widget([
            'icon' => 'bi-pencil-square',
            'size' => Icon::SIZE_16,
            'options' => ['class' => 'flex-shrink-0 me-2'],
        ]) .
        Html::tag('span', Yii::t('app', 'Edit'), ['class' => 'align-middle']),
        $editUrl,
        [
            'class'  => 'btn btn-sm btn-secondary d-inline-flex align-items-center rounded-pill',
            'encode' => false,
        ]
    ) ?>

    <?= Html::a(
        Icon::widget([
            'icon' => 'bi-trash',
            'size' => Icon::SIZE_16,
            'options' => ['class' => 'flex-shrink-0 me-2'],
        ]) .
        Html::tag('span', Yii::t('app', 'Delete'), ['class' => 'align-middle']),
        'javascript:void(0);',
        [
            'class' => 'btn btn-sm btn-outline-danger js-delete d-inline-flex align-items-center rounded-pill',
            'data-href' => $deleteUrl,
            'encode' => false,
        ]
    ) ?>
</div>