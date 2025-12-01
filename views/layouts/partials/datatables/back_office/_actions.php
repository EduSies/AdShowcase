<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

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
        Yii::t('app', 'Edit'),
        $editUrl,
        ['class' => 'btn btn-sm btn-secondary']
    ) ?>

    <?= Html::a(
        Yii::t('app', 'Delete'),
        'javascript:void(0);',
        [
            'class' => 'btn btn-sm btn-outline-danger js-delete',
            'data-href' => $deleteUrl,
        ]
    ) ?>
</div>