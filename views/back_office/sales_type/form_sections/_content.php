<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\SalesTypeForm $model */
/** @var $isUpdate */
/** @var Yii\bootstrap5\ActiveForm $form */

?>

<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'name')
                ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Format name')]) ?>
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