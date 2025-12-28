<?php

use app\widgets\Icon;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $filters array */
/* @var $creatives array */
/* @var $pageTitle string */
/* @var $ajaxUrl string */
/* @var $ajaxUrlCreateList string */
/* @var $ajaxUrlToggleItem string */
/* @var $ajaxUrlGetDropdown string */
/* @var $availableOptions array */

$this->title = $pageTitle;

$id_filter_products = 'filter-products';
$id_filter_formats = 'filter-formats';
$id_filter_devices = 'filter-devices';
$id_filter_countries = 'filter-countries';

$this->registerJsVar('ajaxUrlCatalog', $ajaxUrl);
$this->registerJsVar('ajaxUrlCreateList', $ajaxUrlCreateList);
$this->registerJsVar('ajaxUrlToggleItem', $ajaxUrlToggleItem);
$this->registerJsVar('ajaxUrlGetDropdown', $ajaxUrlGetDropdown);
$this->registerJsVar('initialAvailableOptions', $availableOptions);

$this->registerJsFile('@web/js/catalog.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/modal-share.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/favorites.js', ['depends' => [\yii\web\JqueryAsset::class]]);

?>

<div id="filter-content" class="filter-content">
    <div class="adshowcase-bg-filter"></div>
    <div class="container">

        <div class="row">
            <div class="col-lg-7 col-md-12 col-12">
                <div class="text-banner-adshowcase">
                    <div class="title-adshowcase mt-4 mt-sm-5"><?= Yii::t('app','AdShowcase') ?></div>
                    <div class="subtitle-adshowcase mt-4 mb-md-2 mb-xl-2"><?= Yii::t("app", "Browse innovative creatives from top advertisers and agencies to inspire your next campaign") ?></div>
                </div>
            </div>
        </div>

        <div id="search-filter" class="position-relative row mt-xl-4 mt-md-4 d-none d-lg-flex">

            <div class="col-sm-3">
                <?= Html::dropDownList(
                    'products',
                    null,
                    $filters['industry']['items'],
                    array_merge([
                        'id' => $id_filter_products,
                        'class' => 'form-select py-1 bg-white border-0 shadow-sm text-muted cursor-pointer',
                        'prompt' => Yii::t('app', 'Industry'),
                        'data-placeholder' => Yii::t('app', 'Industry'),
                    ], $filters['industry']['attributes']) // Fusionamos los atributos data-slug
                ) ?>
            </div>

            <div class="col-sm-3">
                <?= Html::dropDownList(
                    'formats',
                    null,
                    $filters['formats']['items'],
                    array_merge([
                        'id' => $id_filter_formats,
                        'class' => 'form-select py-1 bg-white border-0 shadow-sm text-muted cursor-pointer',
                        'prompt' => Yii::t('app', 'Formats'),
                        'data-placeholder' => Yii::t('app', 'Formats'),
                    ], $filters['formats']['attributes'])
                ) ?>
            </div>

            <div class="col-sm-3">
                <?= Html::dropDownList(
                    'devices',
                    null,
                    $filters['devices']['items'],
                    array_merge([
                        'id' => $id_filter_devices,
                        'class' => 'form-select py-1 bg-white border-0 shadow-sm text-muted cursor-pointer',
                        'prompt' => Yii::t('app', 'Devices'),
                        'data-placeholder' => Yii::t('app', 'Devices'),
                    ], $filters['devices']['attributes'])
                ) ?>
            </div>

            <div class="col-sm-3">
                <?= Html::dropDownList(
                    'countries',
                    null,
                    $filters['countries']['items'],
                    array_merge([
                        'id' => $id_filter_countries,
                        'class' => 'form-select py-1 bg-white border-0 shadow-sm text-muted cursor-pointer',
                        'prompt' => Yii::t('app', 'Country'),
                        'data-placeholder' => Yii::t('app', 'Country'),
                    ], $filters['countries']['attributes'])
                ) ?>
            </div>

        </div>

    </div>
</div>

<div id="search-filter-tags" class="container-fluid bg-primary position-relative" style="display: none;">
    <div class="container">
        <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 m-0">
            <div id="search-filter-preview" class="p-0"></div>
        </div>
    </div>
</div>

<div id="control-scroll-filter" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="container">
        <div id="cards-container" class="row">
            <?= $creatives ?>
        </div>
    </div>
</div>

<template id="skeleton-template">
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4 fade-in-card skeleton-wrapper">
        <?= $this->render('_item-creative-skeleton') ?>
    </div>
</template>

<template id="filter-pill-template">
    <span class="badge rounded-pill text-bg-light shadow-sm d-flex align-items-center gap-1 color-main-2 fw-normal ps-3 filter-pill-item">
        <span class="pill-text"></span>
            <?= Icon::widget([
                'icon' => 'bi-x',
                'size' => Icon::SIZE_16,
                'options' => ['class' => 'cursor-pointer btn-close-pill']
            ]) ?>
    </span>
</template>

<template id="filter-delete-all-template">
    <div class="d-flex align-items-center w-100 justify-content-between filter-tags-wrapper">
        <div class="d-flex flex-wrap gap-2 py-3 pills-container"></div>
        <div class="ms-auto ps-3">
            <?= Html::a(
                Html::tag('span', Yii::t('app', 'Clear all filters'), ['class' => 'delete-text']) .
                Icon::widget(['icon' => 'bi-trash', 'size' => Icon::SIZE_16, 'options' => ['class' => 'ms-2']]),
                '#', // URL (hash porque el JS previene el default)
                [
                    'class' => 'btn-delete-all-filters text-white text-decoration-none d-flex align-items-center',
                    'title' => Yii::t('app', 'Clear all filters')
                ]
            ) ?>
        </div>
    </div>
</template>

<?= $this->render('@adshowcase.layouts/partials/_modal-share') ?>