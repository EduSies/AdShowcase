<?php

use yii\helpers\Html;
use app\widgets\Icon;

/** @var array $creative */
/** @var array $listsFavorites Array con las listas del usuario */

?>

<div class="list-favorites-screen">
    <div class="overflow-auto" style="max-height: 300px;">
        <div class="d-grid gap-2 p-3">
            <?= $this->render('_list-favorites-items', [
                'listsFavorites' => $listsFavorites,
                'creativeHash' => $creative['hash'],
            ]) ?>
        </div>
    </div>

    <?= Html::button(
        Html::tag('span', Yii::t('app', 'Create list')) .
        Icon::widget(['icon' => 'bi-plus-lg', 'size' => Icon::SIZE_24]),
        [
            'class' => 'btn btn-primary w-100 rounded-0 py-2 d-flex justify-content-between align-items-center create-list-favorite-btn',
            'type' => 'button'
        ]
    ) ?>
</div>

<div class="create-list-favorite-screen" style="display: none;">
    <?= Html::button(
        Icon::widget(['icon' => 'bi-chevron-left']) .
        Html::tag('span', Yii::t('app', 'Create list'), ['class' => 'h5 mb-0 text-muted']),
        [
            'class' => 'btn btn-link text-decoration-none w-100 text-start p-3 d-flex align-items-center gap-2 color-main-2 back-to-list-btn',
            'type' => 'button'
        ]
    ) ?>

    <div class="p-3 pt-0">
        <label class="form-label small text-muted ms-3 mb-1"><?= Yii::t('app', 'Name') ?></label>
        <?= Html::textInput('new_list_name', '', [
            'class' => 'form-control new-list-input',
            'placeholder' => Yii::t('app', 'Enter list name'),
        ]) ?>
    </div>

    <?= Html::button(
        Html::tag('span', Yii::t('app', 'Save')) .
        Icon::widget(['icon' => 'bi-check-lg', 'size' => Icon::SIZE_24]),
        [
            'class' => 'btn btn-primary w-100 rounded-0 py-2 mt-auto d-flex justify-content-between align-items-center save-new-list-btn',
            'type' => 'button',
            'data' => [
                'creative-hash' => $creative['hash']
            ]
        ]
    ) ?>
</div>