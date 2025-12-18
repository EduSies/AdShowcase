<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\BrandForm $model */
/** @var bool $isUpdate */
/** @var array $status */
/** @var Yii\bootstrap5\ActiveForm $form */

use app\widgets\Icon;
use yii\helpers\Html;

$submitIcon  = $isUpdate ? 'bi-pencil-square' : 'bi-plus-circle';
$submitLabel = $isUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Create');

?>

<div class="row g-3">
    <div class="col-md-6">
        <?= $form->field($model, 'name')
            ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Brand name')]) ?>
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
        <?= $form->field($model, 'status')->dropDownList(
            $status,
            ['prompt' => Yii::t('app', 'Select status')]
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
                        'options' => ['class' => 'flex-shrink-0'],
                    ]) .
                    Html::tag('span', $submitLabel, ['class' => 'ms-2']),
                    [
                        'class'  => $isUpdate ? 'btn btn-primary' : 'btn btn-success',
                        'encode' => false,
                    ]
            ) ?>
        </div>
    </div>
</div>