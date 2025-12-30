<?php

/** @var yii\web\View $this */
/** @var string $title */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \app\models\forms\auth\LoginForm $model */

use app\widgets\Icon;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login d-flex flex-column justify-content-center align-items-center py-5">
    <div class="w-100 d-flex flex-column align-items-center" style="max-width: 450px;min-width: 450px;">

        <div class="text-center mb-4">
            <h1 class="h3 mb-3 fw-normal"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted"><?= Yii::t('app', 'Please fill out the following fields to login:') ?></p>
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

        <?= $form->field($model, 'login', [
            'options' => ['class' => 'form-floating mb-3'],
            'template' => "{input}\n{label}\n{error}",
        ])->textInput([
            'autofocus' => true,
            'placeholder' => '',
            'class' => 'form-control',
        ])->label(Yii::t('app', 'Email or username')) ?>

        <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-floating mb-3'],
            'template' => "{input}\n{label}\n{error}",
        ])->passwordInput([
            'placeholder' => '',
            'class' => 'form-control',
        ])->label(Yii::t('app', 'Password')) ?>

        <div class="mb-3">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'class' => 'form-check-input',
                'template' => "<div class=\"form-check\">\n{input}\n{label}\n{error}\n</div>",
            ])->label(Yii::t('app', 'Remember')) ?>
        </div>

        <div class="d-grid gap-2 mt-4">
            <?= Html::submitButton(
                '<span>' . Yii::t('app', 'Login') . '</span>' .
                Icon::widget(['icon' => 'bi-arrow-right', 'size' => Icon::SIZE_24, 'options' => ['class' => 'ms-2']]),
                ['class' => 'btn btn-primary rounded-pill btn-lg d-flex justify-content-center align-items-center', 'name' => 'login-button']
            ) ?>
        </div>

        <div class="text-center mt-3">
            <?= Html::a(Yii::t('app', 'Did you forget password?'), '#', ['class' => 'text-decoration-none small']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>