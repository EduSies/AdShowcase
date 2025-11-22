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

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mb-4"><?= Yii::t('app', 'Please fill out the following fields to login:') ?></p>

    <?php $form = ActiveForm::begin([
            'id' => $model->formName(),
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-4 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
    ]); ?>

    <?= $form->field($model, 'login', [
            'options' => ['class' => 'form-floating mb-3'],
            'template' => "{input}\n{label}\n{error}",
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback'],
    ])->textInput([
            'id' => 'loginInput',
            'autofocus' => true,
            'placeholder' => '',
    ])->label(Yii::t('app', 'Email or username')) ?>

    <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-floating mb-3'],
            'template' => "{input}\n{label}\n{error}",
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback'],
    ])->passwordInput([
            'id' => 'passwordInput',
            'placeholder' => '',
    ])->label(Yii::t('app', 'Password')) ?>

    <?= $form->field($model, 'rememberMe', [
            'template' => '<div class="form-check">{input}{label}</div>{error}',
            'labelOptions' => ['class' => 'form-check-label'],
            'errorOptions' => ['class' => 'invalid-feedback'],
            'options' => ['class' => 'mb-3'],
    ])->checkbox([
            'class' => 'form-check-input'
    ], false)->label(Yii::t('app', 'Remember')) ?>

    <div class="form-group pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <?= Html::a(Yii::t('app', 'Did you forget password?'), '#') ?>
            <?= Html::submitButton(
            '<span>'.Yii::t('app', 'Login').'</span>'.
                    Icon::widget(['icon' => 'bi-arrow-right', 'size' => Icon::SIZE_24, 'options' => ['class' => '']]),
                    ['class' => 'btn btn-primary d-flex gap-2', 'name' => 'login-button']
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>