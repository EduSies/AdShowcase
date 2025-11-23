<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\DeviceForm $model */
/** @var $isUpdate */
/** @var Yii\bootstrap5\ActiveForm $form */

?>

<?php if ($isUpdate): ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
<?php endif; ?>

<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'name')
                ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Format name')]) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'url_slug')
                ->textInput(['maxlength' => true, 'readonly' => true, 'placeholder' => Yii::t('app', 'slug-like-this')])
                ->hint(Yii::t('app', 'Lowercase, numbers and dashes only.')) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'format')
                ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Format format name')]) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'family')
                ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Family name')]) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'experience')
                ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Experience name')]) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'subtype')
                ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Subtype name')]) ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'status')->dropDownList(
            \app\helpers\StatusHelper::statusesFilters(),
            ['prompt' => Yii::t('app', 'Select status')]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="mt-4 d-flex justify-content-end gap-2">
            <?= \yii\bootstrap5\Html::submitButton(
                $isUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Create'),
                ['class' => $isUpdate ? 'btn btn-primary' : 'btn btn-success']
            ) ?>
        </div>
    </div>
</div>