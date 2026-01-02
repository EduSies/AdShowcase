<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\SharedLinkForm $model */
/** @var array $indexRoute */
/** @var string $sharedUrl */
/** @var string $privateUrl */
/** @var bool $isRevoked */
/** @var array $accessLogs */
/** @var string $creativeTitle */
/** @var int $usedCount */
/** @var int $sharedLinkHash */
/** @var string|null $revokedAt */

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
        'options' => ['autocomplete' => 'off'],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback d-block'],
        ],
    ]); ?>

    <?= $this->render('@adshowcase.layouts/partials/_form-section', [
        'title' => Yii::t('app', 'Shared Links'),
        'indexRoute' => $indexRoute,
        'content' => $this->render('form_sections/_content', [
            'model' => $model,
            'form' => $form,
            'isUpdate' => $isUpdate,
            'sharedUrl' => $sharedUrl,
            'privateUrl' => $privateUrl,
            'isRevoked' => $isRevoked,
            'accessLogs' => $accessLogs,
            'creativeTitle' => $creativeTitle,
            'usedCount' => $usedCount,
            'sharedLinkHash' => $sharedLinkHash,
            'revokedAt' => $revokedAt,
        ])
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>