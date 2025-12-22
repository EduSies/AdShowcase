<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\CreativeForm $model */
/** @var bool $isUpdate */
/** @var array $brands */
/** @var array $agencies */
/** @var array $products */
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

<h5 class="mb-3 text-muted"><?= Yii::t('app', 'Creative assets') ?></h5>
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label"><?= Yii::t('app', 'Thumbnail gallery') ?></label>

        <input type="file"
               id="thumbnail-file-input"
               class="form-control"
               accept="image/png, image/jpeg, image/webp"
               data-aspect-ratio="<?= 16/9 ?>"
               data-crop-width="1280"
               data-crop-height="720"
        >

        <?= $form->field($model, 'url_thumbnail')->hiddenInput(['id' => 'crop-data-input'])->label(false) ?>

        <div id="preview-container" class="d-flex flex-column align-items-center my-3" style="display: <?= $model->url_thumbnail ? 'flex' : 'none' ?> !important;">
            <label class="form-label text-muted small mb-2"><?= Yii::t('app', 'Gallery Image Preview') ?></label>
            <img id="thumbnail-preview"
                 src="<?= $model->url_thumbnail ?? '' ?>"
                 class="img-thumbnail shadow-sm cursor-pointer"
                 style="min-height: 180px; width: 100%; height: 100%;"
                 title="<?= Yii::t('app', 'Click to change image') ?>"
            >
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'upload_asset')->fileInput([
            'id' => 'input-upload-asset',
            'class' => 'form-control',
            'accept' => 'video/mp4, image/jpeg, image/png, image/webp'
        ])->hint(Yii::t('app', 'Video (MP4, max 25MB) or Image (JPG/PNG/WEBP, max 2MB).')) ?>

        <?php
        $hasPreview = !empty($model->preview_asset_url);
        $displayClass = $hasPreview ? 'd-flex' : 'd-none';
        ?>

        <div id="asset-preview-container" class="<?= $displayClass ?> flex-column align-items-center my-3">
            <div id="asset-preview-content" class="w-100 h-100 text-center d-inline-flex flex-column align-items-center">
                <?php if ($hasPreview): ?>
                    <?php if (str_starts_with($model->preview_asset_mime, 'video/')): ?>
                        <label class="form-label text-muted small mb-2"><?= Yii::t('app', 'Preview Video Banner') ?></label>
                        <video controls autoplay loop muted playsinline class="img-thumbnail shadow-sm" style="max-height: 400px; max-width: 100%;">
                            <source src="<?= $model->preview_asset_url ?>" type="<?= $model->preview_asset_mime ?>">
                            <?= Yii::t('app', 'Your browser does not support the video tag.') ?>
                        </video>
                    <?php else: ?>
                        <label class="form-label text-muted small mb-2"><?= Yii::t('app', 'Preview Image Banner') ?></label>
                        <img src="<?= $model->preview_asset_url ?>" class="img-thumbnail shadow-sm" style="max-height: 400px; max-width: 100%;">
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'click_url')->textInput(['maxlength' => true, 'placeholder' => 'https://...']) ?>
    </div>
</div>

<h5 class="mt-4 mb-3 text-muted"><?= Yii::t('app', 'General info') ?></h5>
<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'brand_id')->dropDownList(
            $brands,
            ['prompt' => Yii::t('app', 'Select Brand')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'agency_id')->dropDownList(
            $agencies,
            ['prompt' => Yii::t('app', 'Select Agency')]
        ) ?>
    </div>
</div>

<h5 class="mt-4 mb-3 text-muted"><?= Yii::t('app', 'Technical details (filters)') ?></h5>
<div class="row g-3">
    <div class="col-md-4">
        <?= $form->field($model, 'product_id')->dropDownList(
            $products,
            ['prompt' => Yii::t('app', 'Select Product')]
        ) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'format_id')->dropDownList(
            $formats,
            ['prompt' => Yii::t('app', 'Select Format')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <?= $form->field($model, 'device_id')->dropDownList(
            $devices,
            ['prompt' => Yii::t('app', 'Select Device')]
        ) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'country_id')->dropDownList(
            $countries,
            ['prompt' => Yii::t('app', 'Select Country'), 'data-live-search' => 'true', 'class' => 'form-select']
        ) ?>
    </div>
</div>

<h5 class="mt-4 mb-3 text-muted"><?= Yii::t('app', 'Other technical details') ?></h5>
<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'sales_type_id')->dropDownList(
            $salesTypes,
            ['prompt' => Yii::t('app', 'Select Sales Type')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'language_id')->dropDownList(
            $languages,
            ['prompt' => Yii::t('app', 'Select Language')]
        ) ?>
    </div>
</div>

<h5 class="mt-4 mb-3 text-muted"><?= Yii::t('app', 'Status & Workflow') ?></h5>
<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'status')->dropDownList(
            $status,
            ['prompt' => Yii::t('app', 'Select Status')]
        ) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-8">
        <?= $form->field($model, 'workflow_status')->dropDownList(
            $workflowStatus,
            ['prompt' => Yii::t('app', 'Select Workflow')]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
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

                    <cropper-selection initial-coverage="0.7" resizable>
                        <cropper-handle action="move" theme-color="rgba(255, 255, 255, 0)"></cropper-handle>
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

<?php

// Preparamos los textos traducidos para usarlos en JS
$txtPreviewVideo = Yii::t('app', 'Preview Video Banner');
$txtPreviewImage = Yii::t('app', 'Preview Image Banner');

$js = <<<JS
    document.getElementById('input-upload-asset').addEventListener('change', function(event) {
        const container = document.getElementById('asset-preview-container');
        const content = document.getElementById('asset-preview-content');
        const file = event.target.files[0];
        
        content.innerHTML = '';
        
        if (file) {
            const objectUrl = URL.createObjectURL(file);
            let element = null;
            let labelText = '';
    
            // Detectar tipo y asignar traducción correcta
            if (file.type.startsWith('image/')) {
                element = document.createElement('img');
                labelText = '{$txtPreviewImage}';
            } else if (file.type.startsWith('video/')) {
                element = document.createElement('video');
                element.controls = true;
                element.autoplay = true; 
                element.loop = true;
                element.muted = true;
                element.playsInline = true;
                labelText = '{$txtPreviewVideo}';
            }
    
            if (element) {
                // 1. Crear y añadir el LABEL primero
                const label = document.createElement('label');
                label.className = 'form-label text-muted small mb-2';
                label.textContent = labelText;
                content.appendChild(label);

                // 2. Configurar y añadir el ELEMENTO multimedia
                element.src = objectUrl;
                element.className = 'img-thumbnail shadow-sm';
                element.style.maxHeight = '400px';
                element.style.maxWidth = '100%';
                
                content.appendChild(element);
                
                // Mostrar contenedor
                container.classList.remove('d-none');
                container.classList.add('d-flex');
            } else {
                // Tipo no soportado
                 container.classList.add('d-none');
                 container.classList.remove('d-flex');
            }
        } else {
            // Sin archivo
            container.classList.add('d-none');
            container.classList.remove('d-flex');
        }
    });

    $('#{$form->id}').on('afterValidateAttribute', function(event, attribute, messages) {
        if (attribute.name === 'url_thumbnail') {
            const visibleInput = document.getElementById('thumbnail-file-input');
            if (messages.length > 0) {
                visibleInput.classList.add('is-invalid');
            } else {
                visibleInput.classList.remove('is-invalid');
            }
        }
    });
JS;

$this->registerJs($js);

?>