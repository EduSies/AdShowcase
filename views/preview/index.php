<?php

/** @var yii\web\View $this */
/** @var array $creative */
/** @var string $ajaxUrlCreateList */
/** @var string $ajaxUrlToggleItem */
/** @var string $ajaxUrlGetDropdown */
/** @var string $iframeClass */
/** @var string $iframeSrc */

use app\widgets\Icon;
use yii\helpers\Html;

$this->registerCssFile('@web/css/preview.css', ['depends' => \app\assets\AppAsset::class]);

$this->registerJsVar('ajaxUrlCreateList', $ajaxUrlCreateList);
$this->registerJsVar('ajaxUrlToggleItem', $ajaxUrlToggleItem);
$this->registerJsVar('ajaxUrlGetDropdown', $ajaxUrlGetDropdown);

$this->registerJsFile('@web/js/preview.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/modal-share.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/favorites.js', ['depends' => [\yii\web\JqueryAsset::class]]);

?>

<div id="creative-name" class="d-flex justify-content-center align-items-center w-100 position-absolute px-4 gap-3">
    <div class="title-preview d-flex d-flex align-items-center justify-content-center gap-3">

        <span class="ellipsis-title-preview fw-semibold">
            <?= Html::encode($creative['title']) ?>
        </span>

        <?= Html::tag('div','', ['class' => 'vr']) ?>

        <span class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-uppercase min-w-fit-content"><?= Yii::t("app", 'Format') ?>:</span>
            <span class="ellipsis-title-preview"><?= Html::encode($creative['viewFormatName']) ?></span>
        </span>

        <?= Html::tag('div','', ['class' => 'vr']) ?>

        <span class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-uppercase min-w-fit-content"><?= Yii::t("app", 'Country') ?>:</span>
            <span class="ellipsis-title-preview"><?= Html::encode($creative['viewCountryName']) ?></span>
        </span>

    </div>

    <?php if ($creative['canFavorite']): ?>
        <div class="container-favorite position-relative d-flex align-items-center gap-3">
            <?= Html::tag('div','', ['class' => 'vr']) ?>
            <div class="dropdown">
                <?= Html::button(
                    Icon::widget([
                        'icon' => $creative['viewFavIcon'],
                        'size' => Icon::SIZE_24
                    ]),
                    [
                        'class' => 'btn btn-link color-main-1 p-0 shadow-none cursor-pointer position-relative icon-favorite-card',
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
        </div>
    <?php endif; ?>
</div>

<div id="content-preview" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div id="iframe-class" class="<?= $iframeClass ?>">
        <iframe id="iframe_web" scrolling="yes" allowfullscreen="" allow="autoplay" width="" height="" src="<?= $iframeSrc ?>"></iframe>
    </div>
</div>

<?= $this->render('@adshowcase/views/layouts/partials/_modal-share') ?>