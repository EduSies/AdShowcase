<?php

/** @var yii\web\View $this */
/** @var array $listsFavorites */
/** @var string $filteredListName */
/** @var string $filteredListHash */
/** @var bool $isFavoritesDetail */

use yii\helpers\Html;
use app\widgets\Icon;

if (!$isFavoritesDetail):

?>

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="h2 mb-2">
                <?= Yii::t('app', 'Lists') ?>
            </h1>
            <p class="text-muted mb-0">
                <?= Yii::t('app', 'Sort your favorites into different lists and save them here.') ?>
            </p>
        </div>
    </div>

    <div class="mb-4 dropdown">
        <?= Html::button(
            Html::tag('span', Yii::t('app', 'Create list')) .
            Icon::widget(['icon' => 'bi-plus-lg', 'size' => Icon::SIZE_24, 'options' => ['class' => 'ms-2']]),
            [
                'class' => 'btn btn-primary d-inline-flex align-items-center rounded-pill shadow-sm',
                'id' => 'create-list-page-dropdown',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => 'false',
                'data-bs-auto-close' => 'outside',
                'title' => Yii::t('app', 'Create list')
            ]
        ) ?>

        <div class="dropdown-menu overflow-hidden shadow-lg border-0 p-0 mt-2 layer-add-favorites" aria-labelledby="create-list-page-dropdown" style="min-width: 358px;z-index: 1028;">
            <div class="create-list-favorite-screen">
                <?= Html::button(
                    Html::tag('span', Yii::t('app', 'Create list'), ['class' => 'h5 mb-0 text-muted']),
                    [
                        'class' => 'btn btn-link text-decoration-none w-100 text-start p-3 d-flex align-items-center gap-2 color-main-2 cursor-default',
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
                    ]
                ) ?>
            </div>
        </div>
    </div>

<?php else: ?>

    <h5 class="fw-semibold mb-4">
        <?= Yii::t("app", "Your Lists") ?>
    </h5>

<?php endif; ?>

<div class="lists-favorites-section d-flex <?= ($isFavoritesDetail) ? 'flex-column' : 'flex-wrap mb-4 row' ?>">
    <?= $this->render('listsFavoritesSection', [
        'listsFavorites' => $listsFavorites,
        'isFavoritesDetail' => $isFavoritesDetail
    ]); ?>
</div>