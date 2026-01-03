<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\UserForm $model */
/** @var bool $isUpdate */
/** @var array $roles */
/** @var array $status */
/** @var yii\bootstrap5\ActiveForm $form */

use app\assets\CropperJsAsset;
use app\helpers\LangHelper;
use app\helpers\StatusHelper;
use app\widgets\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

// Registramos los assets de CropperJS
CropperJsAsset::register($this);

$submitIcon  = $isUpdate ? 'bi-pencil-square' : 'bi-plus-circle';
$submitLabel = $isUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Create');
$titlePassword = $isUpdate ? Yii::t('app', 'Change password') : Yii::t('app', 'New password');

$showResendButton = $isUpdate && in_array($model->status, [
    StatusHelper::STATUS_INACTIVE,
    StatusHelper::STATUS_PENDING
]);

?>

<h5 class="mb-3 text-muted"><?= Yii::t('app', 'Avatar image') ?></h5>
<div class="row g-3">
    <div class="col-md-10">
        <input type="file"
               id="thumbnail-file-input"
               class="form-control"
               accept="image/png, image/jpeg, image/webp"
               data-aspect-ratio="1"
               data-crop-width="200"
               data-crop-height="200"
        >

        <?= $form->field($model, 'avatar_url')->hiddenInput(['id' => 'crop-data-input'])->label(false) ?>

        <div id="preview-container" class="d-flex flex-column align-items-center my-3" style="display: <?= $model->avatar_url ? 'flex' : 'none' ?> !important;">
            <label class="form-label text-muted small mb-2"><?= Yii::t('app', 'Avatar Preview') ?></label>
            <img id="thumbnail-preview"
                 src="<?= $model->avatar_url ?? '' ?>"
                 class="img-thumbnail shadow-sm rounded-circle cursor-pointer"
                 style="width: 200px; height: 200px; object-fit: cover;"
                 title="<?= Yii::t('app', 'Click to change image') ?>"
            >
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3 text-muted"><?= Yii::t('app', 'User data') ?></h5>
<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'email')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'Email address'),
            ])
        ?>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'username')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'User name'),
            ])
        ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'name')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'First name'),
            ])
        ?>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'surname')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'Last name'),
            ])
        ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'type')->dropDownList(
            $roles,
            ['prompt' => Yii::t('app', 'Select user type')]
        ) ?>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'status')->dropDownList(
            ($isUpdate ? $status : StatusHelper::statusFilter([StatusHelper::STATUS_PENDING])),
            ['prompt' => Yii::t('app', 'Select status')]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'language_id')->dropDownList(
            LangHelper::getLanguageOptions(),
            ['prompt' => Yii::t('app', 'Select language')]
        ) ?>
    </div>
</div>

<h5 class="mt-4 mb-3 text-muted"><?= $titlePassword ?></h5>
<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'password', [
            'template' => '
                <div class="input-group password-group">
                    <div class="form-floating flex-grow-1">
                        {input}
                        {label}
                        {error}
                    </div>
                    <button class="btn btn-outline-secondary js-toggle-password border-start-0" type="button" tabindex="-1">
                        ' . Icon::widget(['icon' => 'bi-eye']) . '
                    </button>
                </div>
                {hint}
            ',
        ])->passwordInput([
                'maxlength' => true,
                'autocomplete' => 'new-password',
                'placeholder' => Yii::t('app', 'Password'),
                'class' => 'form-control border-end-0',
        ])->hint($isUpdate ? Yii::t('app', 'Leave blank to keep current password') : null) ?>
    </div>

    <div class="col-md-5">
        <?= $form->field($model, 'password_repeat', [
            'template' => '
                <div class="input-group password-group">
                    <div class="form-floating flex-grow-1">
                        {input}
                        {label}
                        {error}
                    </div>
                    <button class="btn btn-outline-secondary js-toggle-password border-start-0" type="button" tabindex="-1">
                        ' . Icon::widget(['icon' => 'bi-eye']) . '
                    </button>
                </div>
                {hint}
            ',
        ])->passwordInput([
            'maxlength' => true,
            'autocomplete' => 'new-password',
            'placeholder' => Yii::t('app', 'Repeat password'),
            'class' => 'form-control border-end-0',
        ]) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-10">
        <div class="mt-4 gap-2 d-flex <?= $showResendButton ? 'justify-content-between' : 'justify-content-end' ?>">

            <?php if ($showResendButton): ?>
                <?= Html::a(
                    Icon::widget([
                        'icon' => 'bi-envelope-paper',
                        'size' => Icon::SIZE_24,
                        'options' => ['class' => 'flex-shrink-0'],
                    ]) .
                    Html::tag('span', Yii::t('app', 'Resend Verification'), ['class' => 'ms-2']),
                    'javascript:void(0);',
                    [
                        'class' => 'btn btn-outline-warning rounded-pill d-flex align-items-center js-resend-verification',
                        'encode' => false,
                    ]
                ) ?>
            <?php endif; ?>

            <?= Html::submitButton(
                Icon::widget([
                    'icon' => $submitIcon,
                    'size' => Icon::SIZE_24,
                    'options' => ['class' => 'flex-shrink-0'],
                ]) .
                Html::tag('span', $submitLabel, ['class' => 'ms-2']),
                [
                    'class'  => ($isUpdate ? 'btn btn-primary' : 'btn btn-success') . ' rounded-pill',
                    'encode' => false,
                ]
            ) ?>
        </div>
    </div>
</div>

<div class="modal fade" id="crop-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 60vw; width: 60vw;">
        <div class="modal-content">
            <div class="modal-header border-secondary py-2">
                <h4 class="modal-title text-muted"><?= Yii::t('app', 'Crop Image') ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0" style="height: 60vh; width: 100%; background: #000; overflow: hidden; position: relative;">

                <cropper-canvas background style="width: 100%; height: 100%;">
                    <cropper-image
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
                        'class' => 'btn btn-secondary rounded-pill d-flex align-items-center',
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
                        'class' => 'btn btn-primary rounded-pill d-flex align-items-center',
                        'encode' => false
                    ]
                ) ?>
            </div>

        </div>
    </div>
</div>

<?php

$resendConfirmMsg = Yii::t('app', 'Are you sure? This will reset the user status to Pending and invalidate previous tokens.');
$btnContinue = Yii::t('app', 'Yes, resend');
$btnCancel = Yii::t('app', 'Cancel');
$ajaxUrlResendVerification = Url::to(['back-office/user-resend-verification', 'hash' => $model->hash]);

$js = <<<JS
    $('#{$form->id}').on('afterValidateAttribute', function(event, attribute, messages) {
        if (attribute.name === 'avatar_url') { // Nombre del atributo en UserForm
            const visibleInput = document.getElementById('thumbnail-file-input');
            if (messages.length > 0) {
                visibleInput.classList.add('is-invalid');
            } else {
                visibleInput.classList.remove('is-invalid');
            }
        }
    });
    
    $(document).on('click', '.js-resend-verification', function(e){
        e.preventDefault();
        e.stopPropagation();
    
        var btn = $(this);
        var originalContent = btn.html();
    
        swalFire({
            title: "$resendConfirmMsg",
            confirmButtonText: "$btnContinue",
            cancelButtonText: "$btnCancel",
            customClass: {container: 'swal2-cancel-pr-container'}
        }).then((dialog) => {
            if (dialog.isConfirmed) {
                
                let width = btn.outerWidth();
                let height = btn.outerHeight();
                let spinnerHtml = $('#spinner-template').html();
    
                btn.css({
                    'width': width,
                    'height': height
                }).prop('disabled', true).html(spinnerHtml);
                
                $.ajax({
                    method: 'post',
                    url: "$ajaxUrlResendVerification",
                }).done(function (response) {
                    if (response.success === true) {
                        swalSuccess(response.message);
                        setTimeout(function() { window.location.reload(); }, 2000);
                    } else {
                        swalDanger(response.message);
                        restoreButton(btn, originalContent);
                    }
                }).fail(function() { 
                    swalDanger('Error processing request.'); 
                    restoreButton(btn, originalContent);
                });
            }
        });
        
        function restoreButton(button, content) {
            button.prop('disabled', false)
                  .html(content)
                  .css({'width': '', 'height': ''});
        }
    });
JS;

$this->registerJs($js);

?>