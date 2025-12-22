<?php

use kartik\select2\Select2;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $filters array */
/* @var $creatives array */
/* @var $pageTitle string */
/* @var $ajaxUrl string */

$this->title = $pageTitle;

$this->registerJsVar('ajaxUrl', $ajaxUrl);
$this->registerJsFile('@web/js/catalog-ajax.js', ['depends' => [\yii\web\JqueryAsset::class]]);

?>

<div id="filter-content" class="filter-content">
    <div class="adshowcase-bg-filter"></div>
    <div class="container">

        <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
                <div class="text-banner-adshowcase">
                    <div class="title-adshowcase mt-4 mt-sm-5"><?= Yii::t('app','AdShowcase') ?></div>
                    <div class="subtitle-adshowcase mt-4 mb-md-2 mb-xl-2"><?= Yii::t("app", "Browse innovative creatives from top advertisers and agencies to inspire your next campaign") ?></div>
                </div>
            </div>
        </div>

        <div id="search-filter" class="row mt-xl-4 mt-md-4 d-none d-lg-flex">
            <div class="col-sm-3">
<!--                    <?php
/*                    $industryOptions = [];
                foreach ($filters['industry'] as $key => $name) {
                    $industryOptions[$key] = ['data-url_slug' => Inflector::slug($name)];
                }
                */?>
                --><?php /*= exte\select2\Select2::widget([
                        'id' => $id_filter_products,
                        'name' => 'format',
                        'data' => $filters['industry'],
                        'value' => ($isFilterProducts) ? array_keys($filtered_filters['industry']) : [],
                        'options' => [
                                'placeholder' => t('adshowcase', 'Industry'),
                                'options' => $industryOptions,
                        ],
                        'valuesPreviewContainer' => '#search-filter-preview',
                        'size' => \exte\select2\Select2::SMALL,
                        'hideSearch' => true,
                        'pluginOptions' => [
                                'allowClear' => false,
                                'multiple' => true,
                                'tags' => false,
                                'closeOnSelect' => false,
                        ],
                        'pluginEvents' => [
                                'change' => 'function() {}',
                        ]
                ]); */?>
            </div>

            <div class="col-sm-3">
<!--                    <?php
/*                    $formatsOptions = [];
                foreach ($filters['formats'] as $key => $name) {
                    $formatsOptions[$key] = ['data-url_slug' => Inflector::slug($name)];
                }
                */?>
                --><?php /*= exte\select2\Select2::widget([
                        'id' => $id_filter_formats,
                        'name' => 'format',
                        'data' => $filters['formats'],
                        'value' => ($isFilterFormats) ? array_keys($filtered_filters['formats']) : [],
                        'options' => [
                                'placeholder' => t('adshowcase','Formats'),
                                'options' => $formatsOptions,
                        ],
                        'valuesPreviewContainer' => '#search-filter-preview',
                        'size' => \exte\select2\Select2::SMALL,
                        'hideSearch' => true,
                        'pluginOptions' => [
                                'allowClear' => false,
                                'multiple' => true,
                                'tags' => false,
                                'closeOnSelect' => false,
                        ],
                        'pluginEvents' => [
                                'change' => 'function() {}',
                        ]
                ]); */?>
            </div>

            <div class="col-sm-3">
<!--                    <?php
/*                    $devicesOptions = [];
                foreach ($filters['devices'] as $key => $name) {
                    $devicesOptions[$key] = ['data-url_slug' => Inflector::slug($name)];
                }
                */?>
                --><?php /*= exte\select2\Select2::widget([
                        'id' => $id_filter_devices,
                        'name' => 'format',
                        'data' => $filters['devices'],
                        'value' => ($isFilterDevices) ? array_keys($filtered_filters['devices']) : [],
                        'options' => [
                                'placeholder' => t('adshowcase','Devices'),
                                'options' => $devicesOptions,
                        ],
                        'valuesPreviewContainer' => '#search-filter-preview',
                        'size' => \exte\select2\Select2::SMALL,
                        'hideSearch' => true,
                        'pluginOptions' => [
                                'allowClear' => false,
                                'multiple' => true,
                                'tags' => false,
                                'closeOnSelect' => false,
                        ],
                        'pluginEvents' => [
                                'change' => 'function() {}',
                        ]
                ]); */?>
            </div>

            <div class="col-sm-3">
<!--                    <?php
/*                    $countriesOptions = [];
                foreach ($filters['countries'] as $key => $name) {
                    $countriesOptions[$key] = ['data-url_slug' => Inflector::slug($name)];
                }
                */?>
                --><?php /*= exte\select2\Select2::widget([
                        'id' => $id_filter_countries,
                        'name' => 'format',
                        'data' => $filters['countries'],
                        'value' => ($isFilterCountries) ? array_keys($filtered_filters['countries']) : [],
                        'options' => [
                                'placeholder' => t('adshowcase','Country'),
                                'options' => $countriesOptions,
                        ],
                        'valuesPreviewContainer' => '#search-filter-preview',
                        'size' => \exte\select2\Select2::SMALL,
                        'hideSearch' => true,
                        'pluginOptions' => [
                                'allowClear' => false,
                                'multiple' => true,
                                'tags' => false,
                                'closeOnSelect' => false,
                        ],
                        'pluginEvents' => [
                                'change' => 'function() {}',
                        ]
                ]); */?>
            </div>
        </div>

    </div>
</div>

<div id="search-filter-tags" class="container-fluid bg-primary d-none d-lg-flex">
    <div class="container">
        <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 m-0">
            <div id="search-filter-preview" class="p-0"></div>
        </div>
    </div>
</div>

<!--    <div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold">EXTE Showcase</h2>
        <p class="text-muted">Browse innovative creatives</p>
    </div>
</div>

<div class="row g-3 mb-5" id="filters-container">

    <div class="col-md-3">
        <label for="filter_products" class="form-label small text-muted fw-bold"><?php /*= Yii::t('app', 'Industry') */?></label>
        <?php /*= \yii\helpers\Html::dropDownList(
                'filter_products',
                null,
                $filters['industry'],
                [
                        'id' => 'filter_products',
                        'class' => 'form-select',
                        'multiple' => true,
                        'size' => 4, // Altura visible (4 líneas) para facilitar selección múltiple
                        'onchange' => 'triggerFilter()',
                        'aria-label' => Yii::t('app', 'Industry')
                ]
        ) */?>
        <div class="form-text x-small text-end fst-italic mt-0" style="font-size: 0.7rem;">
            <?php /*= Yii::t('app', 'Ctrl+Click to select multiple') */?>
        </div>
    </div>

    <div class="col-md-3">
        <label for="filter_formats" class="form-label small text-muted fw-bold"><?php /*= Yii::t('app', 'Formats') */?></label>
        <?php /*= \yii\helpers\Html::dropDownList(
                'filter_formats',
                null,
                $filters['formats'],
                [
                        'id' => 'filter_formats',
                        'class' => 'form-select',
                        'multiple' => true,
                        'size' => 4,
                        'onchange' => 'triggerFilter()',
                        'aria-label' => Yii::t('app', 'Formats')
                ]
        ) */?>
    </div>

    <div class="col-md-3">
        <label for="filter_devices" class="form-label small text-muted fw-bold"><?php /*= Yii::t('app', 'Devices') */?></label>
        <?php /*= \yii\helpers\Html::dropDownList(
                'filter_devices',
                null,
                $filters['devices'],
                [
                        'id' => 'filter_devices',
                        'class' => 'form-select',
                        'multiple' => true,
                        'size' => 4,
                        'onchange' => 'triggerFilter()',
                        'aria-label' => Yii::t('app', 'Devices')
                ]
        ) */?>
    </div>

    <div class="col-md-3">
        <label for="filter_countries" class="form-label small text-muted fw-bold"><?php /*= Yii::t('app', 'Country') */?></label>
        <?php /*= \yii\helpers\Html::dropDownList(
                'filter_countries',
                null,
                $filters['countries'],
                [
                        'id' => 'filter_countries',
                        'class' => 'form-select',
                        'multiple' => true,
                        'size' => 4,
                        'onchange' => 'triggerFilter()',
                        'aria-label' => Yii::t('app', 'Country')
                ]
        ) */?>
    </div>

</div>-->

<div id="control-scroll-filter" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="container">
        <div id="cards-container" class="row">
            <?= $creatives ?>
        </div>
    </div>
</div>

<div id="loader" class="text-center py-5 d-none">
    <div class="spinner-border text-primary" role="status"></div>
</div>

<div id="scroll-trigger" class="py-5"></div>