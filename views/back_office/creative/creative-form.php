<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\CreativeForm $model */
/** @var array $indexRoute */
/** @var array $brands */
/** @var array $agencies */
/** @var array $formats */
/** @var array $devices */
/** @var array $salesTypes */
/** @var array $languages */
/** @var array $status */
/** @var array $workflowStatus */
/** @var array $countries */

use yii\bootstrap5\ActiveForm;

$isUpdate = !empty($model->hash);

?>

<div class="<?= $model->formName() ?>">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnChange' => true,
        'options' => [
            'autocomplete' => 'off',
            'enctype' => 'multipart/form-data'
        ],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback d-block'],
        ],
    ]); ?>

    <?= $this->render('@adshowcase.layouts/partials/_form-section', [
        'title' => Yii::t('app', 'Creative'),
        'indexRoute' => $indexRoute,
        'content' => $this->render('form_sections/_content', [
            'model' => $model,
            'form' => $form,
            'isUpdate' => $isUpdate,
            'brands' => $brands,
            'agencies' => $agencies,
            'formats' => $formats,
            'devices' => $devices,
            'salesTypes' => $salesTypes,
            'languages' => $languages,
            'countries' => $countries,
            'status' => $status,
            'workflowStatus' => $workflowStatus,
        ])
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>