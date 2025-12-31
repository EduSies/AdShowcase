<?php

/** @var array $creative */
/** @var array $listsFavorites */

use app\widgets\Icon;
use yii\helpers\Html;
use app\widgets\Flag;

?>

<div class="card h-100 border-0 shadow-sm creative-card position-relative">
    <?php if ($creative['canFavorite']): ?>
        <div class="position-absolute top-0 end-0 p-2 dropdown">
            <?= Html::button(
                Icon::widget(['icon' => $creative['viewFavIcon'], 'size' => Icon::SIZE_24]),
                [
                    'class' => 'btn btn-link text-white p-0 shadow-none cursor-pointer position-relative icon-favorite-card',
                    'id' => 'favDropdown-' . $creative['hash'],
                    'data-bs-toggle' => 'dropdown',
                    'data-bs-auto-close' => 'outside',
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false',
                    'style' => 'z-index: 1027;',
                    'data-creative-hash' => $creative['hash'],
                    'title' => Yii::t('app', 'Add to favorites')
                ]
            ) ?>

            <div class="dropdown-menu dropdown-menu-center overflow-hidden shadow-lg border-0 p-0 mt-2 layer-add-favorites"
                 aria-labelledby="favDropdown-<?= $creative['hash'] ?>"
                 style="min-width: 358px;z-index: 1028;"
            >
                <div class="content-loader color-main-1 p-4" style="display: none;"></div>
                <div class="dropdown-content-wrapper"></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="position-relative overflow-hidden rounded-top">
        <div class="ratio ratio-16x9">
            <a href="<?= $creative['viewDetailUrl'] ?>" class="d-block w-100 h-100">
                <img src="<?= $creative['url_thumbnail'] ?>"
                     class="card-img-top object-fit-cover w-100 h-100"
                     alt="<?= Html::encode($creative['title']) ?>"
                >
            </a>
        </div>

        <div class="position-absolute top-0 start-0 w-100"
             style="height: 40%; background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0) 100%); pointer-events: none;"
        >
        </div>
    </div>

    <div class="card-body pt-0 d-flex flex-column align-items-center text-center position-relative overflow-hidden">

        <div class="position-relative" style="top: -1px; z-index: 3;">
            <?= Flag::widget([
                'tag' => 'span',
                'country' => $creative['viewCountryCode'],
                'options' => [
                    'class' => 'd-block shadow-sm',
                    'style' => 'width: 30px;height: 23px; object-fit: cover;'
                ]
            ]) ?>
        </div>

        <div class="text-uppercase text-muted fw-bold tracking-wide mb-1 mt-3" style="font-size: 0.65rem; letter-spacing: 1px;">
            <?= Html::encode($creative['viewFormatName']) ?>
        </div>

        <h6 class="card-title fw-bold mb-1 text-truncate w-100" title="<?= Html::encode($creative['title']) ?>">
            <a href="<?= $creative['viewDetailUrl'] ?>" class="text-decoration-none stretched-link" style="color: var(--main-color-1);">
                <?= Html::encode($creative['title']) ?>
            </a>
        </h6>

        <div class="small text-secondary mb-2 text-truncate w-100">
            <?= Html::encode($creative['viewAgencyName']) ?>
        </div>

        <div class="mt-auto pt-2 w-100 text-muted opacity-50">
            <?= Icon::widget([
                'icon' => $creative['viewDeviceIcon'],
                'size' => Icon::SIZE_24,
            ]) ?>
        </div>

        <?php if ($creative['canShare']): ?>
            <div class="card-hover-overlay position-absolute start-0 bottom-0 w-100 h-100 d-flex align-items-center justify-content-center">
                <div class="ovelay-card w-100 h-100 position-fixed"></div>

                <?= Html::button(
                    Icon::widget(['icon' => 'bi-share-fill', 'size' => Icon::SIZE_24]) .
                    Html::tag('span', Yii::t('app', 'Share')),
                    [
                        'class' => 'btn btn-outline-light rounded-pill px-4 position-relative d-flex align-items-center gap-2 shadow-lg action-share-btn',
                        'style' => 'z-index: 2;',
                        'data' => [
                            'bs-toggle' => 'modal',
                            'bs-target' => '#shareModal',
                            'creative-hash' => $creative['hash'],
                            'creative-title' => $creative['title'],
                            'creative-format' => $creative['viewFormatName'],
                            'creative-agency' => $creative['viewAgencyName'],
                        ]
                    ]
                ) ?>
            </div>
        <?php endif; ?>

    </div>
</div>