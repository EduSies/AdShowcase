<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\AgencyForm $model */

use yii\bootstrap5\ActiveForm;

$isUpdate = !empty($model->id);

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

        <?= $this->render('@adshowcase.layouts/form-section', [
                'title' => Yii::t('app', 'Agency'),
                'content' => $this->render('form-sections/content', [
                    'model' => $model,
                    'form' => $form,
                    'isUpdate' => $isUpdate,
                ])
        ]) ?>

        <?php ActiveForm::end(); ?>
    </div>