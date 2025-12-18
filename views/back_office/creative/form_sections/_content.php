<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\CreativeForm $model */
/** @var bool $isUpdate */
/** @var array $brands */
/** @var array $agencies */
/** @var array $formats */
/** @var array $devices */
/** @var array $salesTypes */
/** @var array $languages */
/** @var array $status */
/** @var array $workflowStatus */
/** @var array $countries */
/** @var yii\bootstrap5\ActiveForm $form */

use app\assets\CropperJsAsset;
use app\widgets\Icon;
use yii\helpers\Html;

CropperJsAsset::register($this);

$submitIcon  = $isUpdate ? 'bi-pencil-square' : 'bi-plus-circle';
$submitLabel = $isUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Create');

?>

<h5 class="mb-3 text-muted"><?= Yii::t('app', 'Creative Assets') ?></h5>
<div class="row g-3">
    <div class="col-md-9 mb-3">
        <label class="form-label"><?= Yii::t('app', 'Thumbnail gallery') ?></label>

        <?= $form->field($model, 'url_thumbnail', ['options' => ['class' => 'd-none']])->hiddenInput(['id' => 'crop-data-input'])->label(false) ?>

        <input type="file" id="thumbnail-file-input" class="form-control" accept="image/png, image/jpeg, image/webp">

        <div id="preview-container" class="d-flex flex-column align-items-center my-3" style="display: <?= $model->url_thumbnail ? 'flex' : 'none' ?> !important;">
            <label class="form-label text-muted small mb-2"><?= Yii::t('app', 'Gallery Image Preview') ?></label>
            <img id="thumbnail-preview" src="<?= $model->url_thumbnail ?? '' ?>" class="img-thumbnail" style="max-height: 180px;">
        </div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-9">
        <?= $form->field($model, 'upload_asset', ['enableAjaxValidation' => false])->fileInput([
                'class' => 'form-control',
                'accept' => 'video/mp4, image/jpeg, image/png, image/webp'
        ])->hint(Yii::t('app', 'Video (MP4, max 25MB) or Image (JPG/PNG/WEBP, max 2MB).')) ?>
    </div>
</div>

<h5 class="mt-5 mb-3 text-muted"><?= Yii::t('app', 'General Info') ?></h5>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'brand_id')->dropDownList(
            $brands,
            ['prompt' => Yii::t('app', 'Select Brand')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'agency_id')->dropDownList(
            $agencies,
            ['prompt' => Yii::t('app', 'Select Agency')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'click_url')->textInput(['maxlength' => true, 'placeholder' => 'https://...']) ?>
    </div>
</div>

<h5 class="mt-5 mb-3 text-muted"><?= Yii::t('app', 'Technical Details') ?></h5>
<div class="row g-3">
    <div class="col-md-3">
        <?= $form->field($model, 'format_id')->dropDownList(
            $formats,
            ['prompt' => Yii::t('app', 'Select Format')]
        ) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'device_id')->dropDownList(
                $devices,
                ['prompt' => Yii::t('app', 'Select Device')]
        ) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'sales_type_id')->dropDownList(
                $salesTypes,
                ['prompt' => Yii::t('app', 'Select Sales Type')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'language_id')->dropDownList(
                $languages,
                ['prompt' => Yii::t('app', 'Select Language')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'country_id')->dropDownList(
            $countries,
            ['prompt' => Yii::t('app', 'Select Country'), 'data-live-search' => 'true', 'class' => 'form-select']
        ) ?>
    </div>
</div>

<h5 class="mt-5 mb-3 text-muted"><?= Yii::t('app', 'Status & Workflow') ?></h5>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'status')->dropDownList(
            $status,
            ['prompt' => Yii::t('app', 'Select Status')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'workflow_status')->dropDownList(
            $workflowStatus,
            ['prompt' => Yii::t('app', 'Select Workflow')]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="mt-4 d-flex justify-content-end gap-2">
            <?= Html::submitButton(
                Icon::widget([
                    'icon' => $submitIcon,
                    'size' => Icon::SIZE_24,
                    'options' => ['class' => 'flex-shrink-0']
                ]) .
                Html::tag('span', $submitLabel, ['class' => 'ms-2']),
                [
                    'class' => $isUpdate ? 'btn btn-primary' : 'btn btn-success',
                    'encode' => false
                ]
            ) ?>
        </div>
    </div>
</div>

<div class="modal fade" id="crop-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 60vw; width: 60vw;">
        <div class="modal-content">
            <div class="modal-header border-secondary py-2">
                <h6 class="modal-title"><?= Yii::t('app', 'Crop Image') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0" style="height: 60vh; width: 100%; background: #000; overflow: hidden; position: relative;">

                <cropper-canvas background style="width: 100%; height: 100%;">
                    <cropper-image
                            src=""
                            alt="Picture"
                            translatable="true"
                            scalable="true"
                            initial-center-size="contain"
                            rotatable>
                    </cropper-image>

                    <cropper-shade hidden></cropper-shade>

                    <cropper-selection movable resizable>
                        <cropper-grid role="grid" covered></cropper-grid>
                        <cropper-crosshair centered></cropper-crosshair>
                        <cropper-handle action="move" theme-color="rgba(255, 255, 255, 0.35)"></cropper-handle>
                        <cropper-handle action="ne"></cropper-handle>
                        <cropper-handle action="nw"></cropper-handle>
                        <cropper-handle action="se"></cropper-handle>
                        <cropper-handle action="sw"></cropper-handle>
                    </cropper-selection>
                </cropper-canvas>

            </div>

            <div class="modal-footer border-secondary py-2">
                <?= \yii\bootstrap5\Html::button(
                        Icon::widget([
                            'icon' => 'bi-x-lg',
                            'size' => Icon::SIZE_24,
                            'options' => ['class' => 'flex-shrink-0']
                        ]) .
                        Html::tag('span', Yii::t('app', 'Cancel'), ['class' => 'ms-2']),
                        [
                            'class' => 'btn btn-secondary d-flex align-items-center',
                            'data-bs-dismiss' => 'modal',
                            'encode' => false
                        ]
                ) ?>
                <?= Html::button(
                        Icon::widget([
                            'icon' => 'bi-scissors',
                            'size' => Icon::SIZE_24,
                            'options' => ['class' => 'flex-shrink-0']
                        ]) .
                        Html::tag('span', Yii::t('app', 'Crop & Save'), ['class' => 'ms-2']),
                        [
                            'id' => 'btn-crop-save',
                            'class' => 'btn btn-primary d-flex align-items-center',
                            'encode' => false
                        ]
                ) ?>
            </div>
        </div>
    </div>
</div>