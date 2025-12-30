<?php

/* @var $listsFavorites array */
/* @var $isFavoritesDetail bool */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\Icon;

// Variables JS para SweetAlert
$this->registerJsVar('swalFireTitleDeleteList', Yii::t("app", "Delete list"));
$this->registerJsVar('swalFireHtmlDeleteList', Yii::t("app", "You are about to delete your list {NAME_LIST}, this option is not reversible, are you sure?"));

foreach ($listsFavorites as $favorites):
    if ($favorites['hash'] === null) continue;

    // Preparar datos
    $hash = $favorites['hash'];
    $name = $favorites['name'];
    $images = $favorites['images'] ?? [];
    $countImages = count($images);

    ?>

    <div class="<?= ($isFavoritesDetail) ? 'col-12 mb-3' : 'col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12 cards' ?>">

        <div class="card h-100 border-0 shadow-sm list-card-item position-relative bg-white hover-shadow transition-base">
            <a href="<?= Url::toRoute(['favorite/detail', 'hash' => $hash]) ?>" class="d-block text-decoration-none">

                <div class="card-body p-3 pb-4 d-flex justify-content-between align-items-center">

                    <h6 class="text-decoration-none text-dark text-truncate w-75 mb-0 fw-semibold"><?= Html::encode($name) ?></h6>

                    <div class="dropdown">
                        <?= Html::button(
                            Icon::widget(['icon' => 'bi-three-dots-vertical', 'size' => Icon::SIZE_24]),
                            [
                                'class' => 'btn btn-link text-dark p-0 text-decoration-none icon-favorite-actions',
                                'data-bs-toggle' => 'dropdown',
                                'aria-expanded' => 'false',
                                'data-bs-auto-close' => 'outside',
                                'title' => Yii::t('app', 'Actions')
                            ]
                        ) ?>

                        <ul class="dropdown-menu dropdown-menu-center shadow-lg border-0 p-3 mt-2" style="min-width: 358px;">

                            <div class="list-actions-screen">
                                <li class="mb-2">
                                    <?= Html::button(
                                        Icon::widget(['icon' => 'bi-pencil', 'size' => Icon::SIZE_24]) .
                                        Html::tag('span', Yii::t("app", "Edit name")),
                                        [
                                            'class' => 'dropdown-item d-flex align-items-center gap-3 rounded py-2 edit-name-list',
                                            'type' => 'button'
                                        ]
                                    ) ?>
                                </li>
                                <li class="mb-2">
                                    <?= Html::button(
                                        Icon::widget(['icon' => 'bi-folder-symlink', 'size' => Icon::SIZE_24]) .
                                        Html::tag('span', Yii::t("app", "Move to another list")),
                                        [
                                            'class' => 'dropdown-item d-flex align-items-center gap-3 rounded py-2 move-favorites-list',
                                            'type' => 'button'
                                        ]
                                    ) ?>
                                </li>
                                <li>
                                    <?= Html::button(
                                        Icon::widget(['icon' => 'bi-trash', 'size' => Icon::SIZE_24]) .
                                        Html::tag('span', Yii::t("app", "Delete list")),
                                        [
                                            'class' => 'dropdown-item d-flex align-items-center gap-3 rounded py-2 delete-favorites-list',
                                            'type' => 'button',
                                            'data-list-hash' => $hash,
                                            'data-list-name' => Html::encode($name)
                                        ]
                                    ) ?>
                                </li>
                            </div>

                            <div class="edit-name-list-favorite-screen" style="display:none;">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <?= Html::button(
                                        Icon::widget(['icon' => 'bi-chevron-left']),
                                        [
                                            'class' => 'btn btn-link p-0 text-dark back-to-list text-decoration-none',
                                            'type' => 'button'
                                        ]
                                    ) ?>
                                    <span class="fw-bold"><?= Yii::t("app", "Edit name") ?></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-muted"><?= Yii::t("app", "Name") ?></label>
                                    <?= Html::textInput("input-edit-name-list", $name, [
                                        "placeholder" => Yii::t("app", "Enter list name"),
                                        "class" => "form-control",
                                    ]) ?>
                                </div>

                                <?= Html::button(
                                    Html::tag('span', Yii::t('app', 'Save')) .
                                    Icon::widget(['icon' => 'bi-check-lg', 'size' => Icon::SIZE_24]),
                                    [
                                        'class' => 'btn btn-primary w-100 d-flex justify-content-between align-items-center edit-name-list-favorite',
                                        'type' => 'button',
                                        'data-list-hash' => $hash
                                    ]
                                ) ?>
                            </div>

                            <div class="move-list-favorite-screen" style="display:none;">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <?= Html::button(
                                        Icon::widget(['icon' => 'bi-chevron-left']),
                                        [
                                            'class' => 'btn btn-link p-0 text-dark back-to-list text-decoration-none',
                                            'type' => 'button'
                                        ]
                                    ) ?>
                                    <span class="fw-bold"><?= Yii::t("app", "Move to another list") ?></span>
                                </div>

                                <div class="text-muted small mb-2"><?= Yii::t("app", "My lists") ?></div>

                                <div class="overflow-auto" style="max-height: 200px;">
                                    <?php foreach ($listsFavorites as $list): ?>
                                        <?php
                                        // Evitamos mostrar la misma lista en la que estamos
                                        if ($list['hash'] === $hash) continue;

                                        $listImg = !empty($list['images']) ? $list['images'][0] : null;
                                        ?>
                                        <div class="d-flex justify-content-between align-items-center p-2 hover-bg-light rounded mb-1">
                                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                <div class="rounded d-flex align-items-center justify-content-center bg-light border" style="width: 40px; height: 40px; min-width: 40px;">
                                                    <?php if ($listImg): ?>
                                                        <?= Html::img($listImg, ['class' => 'w-100 h-100 object-fit-cover rounded']) ?>
                                                    <?php else: ?>
                                                        <?= Icon::widget(['icon' => 'bi-images', 'size' => Icon::SIZE_24, 'options' => ['class' => 'text-muted']]) ?>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="text-truncate small fw-bold"><?= Html::encode($list['name']) ?></span>
                                            </div>

                                            <?= Html::button(Yii::t('app', 'Move'), [
                                                'class' => 'btn btn-sm btn-outline-secondary move-to-list-favorite',
                                                'data-list-hash' => $hash, // Origen
                                                'data-to-list-hash' => $list['hash'], // Destino
                                            ]) ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </ul>
                    </div>
                </div>

                <div class="card-body p-3 pt-0">
                    <div class="d-flex gap-2" style="height: 87px;">

                        <?php if ($countImages === 0): ?>
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-back text-dark rounded">
                                <?= Icon::widget(['icon' => 'bi-images', 'size' => Icon::SIZE_32]) ?>
                            </div>

                        <?php elseif ($countImages === 1): ?>
                            <div class="w-100 h-100 rounded overflow-hidden">
                                <?= Html::img($images[0], ['class' => 'w-100 h-100 object-fit-cover']) ?>
                            </div>

                        <?php elseif ($countImages === 2): ?>
                            <?php foreach ($images as $img): ?>
                                <div class="w-50 h-100 rounded overflow-hidden">
                                    <?= Html::img($img, ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                </div>
                            <?php endforeach; ?>

                        <?php elseif ($countImages === 3): ?>
                            <?php foreach ($images as $img): ?>
                                <div class="w-100 h-100 rounded overflow-hidden" style="flex: 1;">
                                    <?= Html::img($img, ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                </div>
                            <?php endforeach; ?>

                        <?php elseif ($countImages > 3): ?>
                            <?php for ($i = 0; $i < 2; $i++): ?>
                                <div class="h-100 rounded overflow-hidden" style="width: 33.33%;">
                                    <?= Html::img($images[$i], ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                </div>
                            <?php endfor; ?>

                            <div class="h-100 d-flex align-items-center justify-content-center bg-back text-dark fw-semibold rounded" style="width: 33.33%;">
                                <?= '+' . ($countImages - 2) ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </a>
        </div>
    </div>

<?php endforeach; ?>