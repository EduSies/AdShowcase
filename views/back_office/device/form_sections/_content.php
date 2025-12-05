<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\DeviceForm $model */
/** @var $isUpdate */
/** @var Yii\bootstrap5\ActiveForm $form */

$submitIcon  = $isUpdate ? 'bi-pencil-square' : 'bi-plus-circle';
$submitLabel = $isUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Create');

?>

<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'name')
            ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Device name')]) ?>
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
                    \app\widgets\Icon::widget([
                        'icon' => $submitIcon,
                        'size' => \app\widgets\Icon::SIZE_24,
                        'options' => ['class' => 'flex-shrink-0'],
                    ]) .
                    \yii\helpers\Html::tag('span', $submitLabel, ['class' => 'ms-2']),
                    [
                        'class'  => $isUpdate ? 'btn btn-primary' : 'btn btn-success',
                        'encode' => false,
                    ]
            ) ?>
        </div>
    </div>
</div>