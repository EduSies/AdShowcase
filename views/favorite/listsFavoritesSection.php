<?php

/* @var $listsFavorites array */
/* @var $isFavoritesDetail bool */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\Icon;

// Variables JS para SweetAlert
$this->registerJsVar('swalFireTitleDeleteList', Yii::t("app", "Delete list"));
$this->registerJsVar('swalFireHtmlDeleteList', Yii::t("app", "You are about to delete your list {NAME_LIST}, this option is not reversible, are you sure?"));
$this->registerJsVar('swalFireHtmlRenameList', Yii::t("app", "The list name is the same as the original"));
$this->registerJsVar('swalFireHtmlEmptyList', Yii::t("app", "The list name cannot be empty"));
$this->registerJsVar('swalFireConfirmButton', Yii::t('app', 'Accept'));
$this->registerJsVar('swalFireCancelButton', Yii::t('app', 'Cancel'));

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

            <div class="card-body p-3 pb-4 d-flex justify-content-between align-items-center">
                <a href="<?= Url::toRoute(['favorite/detail', 'hash' => $hash]) ?>" class="text-decoration-none text-dark text-truncate w-75">
                    <h6 class="mb-0 fw-semibold text-truncate"><?= Html::encode($name) ?></h6>
                </a>

                <div class="dropdown">
                    <?= Html::button(
                        Icon::widget(['icon' => 'bi-three-dots-vertical', 'size' => Icon::SIZE_24]),
                        [
                            'id' => 'listDropdown-' . $hash,
                            'class' => 'btn btn-link text-dark p-0 text-decoration-none icon-favorite-actions',
                            'data-bs-toggle' => 'dropdown',
                            'aria-expanded' => 'false',
                            'data-bs-auto-close' => 'outside',
                            'title' => Yii::t('app', 'Actions')
                        ]
                    ) ?>

                    <ul class="dropdown-menu dropdown-menu-center overflow-hidden shadow-lg border-0 p-0 mt-2"
                        aria-labelledby="listDropdown-<?= $hash ?>"
                        style="min-width: 358px;"
                    >
                        <div class="list-actions-screen p-3">
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
                            <?= Html::button(
                                Icon::widget(['icon' => 'bi-chevron-left']) .
                                Html::tag('span', Yii::t('app', 'Edit name'), ['class' => 'h5 mb-0 text-muted']),
                                [
                                    'class' => 'btn btn-link text-decoration-none w-100 text-start p-3 d-flex align-items-center gap-2 color-main-2 back-to-list-btn',
                                    'type' => 'button'
                                ]
                            ) ?>

                            <div class="p-3 pt-0">
                                <label class="form-label small text-muted ms-3 mb-1"><?= Yii::t('app', 'Name') ?></label>
                                <?= Html::textInput("input_edit_name_list", $name, [
                                    "placeholder" => Yii::t("app", "Enter list name"),
                                    "class" => "form-control",
                                    "data-original-name" => $name
                                ]) ?>
                            </div>

                            <?= Html::button(
                                Html::tag('span', Yii::t('app', 'Save')) .
                                Icon::widget(['icon' => 'bi-check-lg', 'size' => Icon::SIZE_24]),
                                [
                                    'class' => 'btn btn-primary w-100 rounded-0 py-2 mt-auto d-flex justify-content-between align-items-center edit-name-list-favorite',
                                    'type' => 'button',
                                    'data-list-hash' => $hash
                                ]
                            ) ?>
                        </div>

                        <div class="move-list-favorite-screen" style="display:none;">
                            <?= Html::button(
                                Icon::widget(['icon' => 'bi-chevron-left']) .
                                Html::tag('span', Yii::t('app', 'Move to another list'), ['class' => 'h5 mb-0 text-muted']),
                                [
                                    'class' => 'btn btn-link text-decoration-none w-100 text-start p-3 d-flex align-items-center gap-2 color-main-2 back-to-list-btn',
                                    'type' => 'button'
                                ]
                            ) ?>

                            <div class="d-flex flex-column gap-2 p-3 overflow-auto" style="max-height: 300px;">
                                <div class="text-muted ms-2 mb-2" style="font-size: 0.9rem;">
                                    <?= Yii::t('app', 'My lists') ?>
                                </div>

                                <?php foreach ($listsFavorites as $list): ?>
                                    <?php
                                    if ($list['hash'] === $hash) continue;
                                    $listImg = !empty($list['images']) ? $list['images'][0] : null;
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center p-2 rounded hover-bg-light transition-base list-favorite">
                                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                                            <div class="rounded d-flex align-items-center justify-content-center text-white bg-main-1 shadow-sm"
                                                 style="width: 50px; height: 50px; min-width: 50px;"
                                            >
                                                <?php if ($listImg): ?>
                                                    <?= Html::img($listImg, ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                                <?php else: ?>
                                                    <?= Icon::widget(['icon' => 'bi-images', 'size' => Icon::SIZE_24]) ?>
                                                <?php endif; ?>
                                            </div>
                                            <h6 class="mb-0 fw-semibold text-truncate" style="max-width: 140px;" title="<?= Html::encode($list['name']) ?>">
                                                <?= Html::encode($list['name']) ?>
                                            </h6>
                                        </div>

                                        <?= Html::button(
                                            '<span>' . Yii::t('app', 'Move') . '</span>' . Icon::widget(['icon' => 'bi-folder-symlink']),
                                            [
                                                'class' => 'btn btn-sm btn-outline-primary rounded-pill px-3 d-flex align-items-center gap-2 move-to-list-favorite',
                                                'data-list-hash' => $hash,
                                                'data-to-list-hash' => $list['hash'],
                                            ]
                                        ) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>

            <a href="<?= Url::toRoute(['favorite/detail', 'hash' => $hash]) ?>" class="d-block text-decoration-none">
                <div class="card-body p-3 pt-0">
                    <div class="d-flex gap-2" style="height: 87px;">
                        <?php if ($countImages === 0): ?>
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-main-1 text-white rounded">
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