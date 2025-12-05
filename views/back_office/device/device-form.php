<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\DeviceForm $model */
/** @var array $indexRoute */

use yii\bootstrap5\ActiveForm;

$isUpdate = !empty($model->hash);

?>

<div class="<?= $model->formName() ?>">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'validateOnBlur' => true,
        'validateOnChange' => true,
        'options' => ['autocomplete' => 'off'],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback d-block'],
        ],
    ]); ?>

    <?= $this->render('@adshowcase.layouts/partials/_form-section', [
            'title' => Yii::t('app', 'Device'),
            'indexRoute' => $indexRoute,
            'content' => $this->render('form_sections/_content', [
                'model' => $model,
                'form' => $form,
                'isUpdate' => $isUpdate,
            ])
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>