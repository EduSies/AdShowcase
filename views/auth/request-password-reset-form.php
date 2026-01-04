<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \app\models\forms\auth\RequestPasswordResetForm $model */
/** @var string $title */

use app\widgets\Icon;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = $title;

?>

<div class="site-request-password-reset d-flex flex-column justify-content-center align-items-center">
    <div class="w-100 d-flex flex-column align-items-center" style="max-width: 700px;min-width: 450px;">

        <div class="text-center mb-4">
            <h1 class="h3 mb-3 fw-normal"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted"><?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there.') ?></p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => $model->formName(),
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'validateOnBlur' => true,
            'validateOnChange' => true,
            'options' => [
                'autocomplete' => 'off',
                'class' => 'shadow-lg'
            ],
        ]); ?>

        <?= $form->field($model, 'email', [
            'options' => ['class' => 'form-floating mb-3'],
            'template' => "{input}\n{label}\n{error}",
        ])->textInput([
            'autofocus' => true,
            'placeholder' => '',
            'class' => 'form-control',
            'type' => 'email'
        ])->label(Yii::t('app', 'Email')) ?>

        <div class="d-grid gap-2 mt-4">
            <?= Html::submitButton(
                '<span>' . Yii::t('app', 'Send') . '</span>' .
                Icon::widget(['icon' => 'bi-envelope', 'size' => Icon::SIZE_24, 'options' => ['class' => 'ms-4']]),
                ['class' => 'btn btn-primary rounded-pill btn-lg d-flex justify-content-center align-items-center']
            ) ?>
        </div>

        <div class="text-center mt-3">
            <?= Html::a(Yii::t('app', 'Return to Login'), ['auth/login'], ['class' => 'text-decoration-none small']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>