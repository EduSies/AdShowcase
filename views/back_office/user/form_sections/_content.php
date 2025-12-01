<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\UserForm $model */
/** @var bool $isUpdate */
/** @var array $roles */
/** @var yii\bootstrap5\ActiveForm $form */

?>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'email')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'Email address'),
            ]
        ) ?>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'username')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'Username'),
            ]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'name')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'First name'),
            ]
        ) ?>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'surname')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'Last name'),
            ]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'type')->dropDownList(
            $roles,
            ['prompt' => Yii::t('app', 'Select user type')]
        ) ?>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'status')->dropDownList(
            \app\helpers\StatusHelper::statusesFilters(),
            ['prompt' => Yii::t('app', 'Select status')]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'language_id')
            ->textInput([
                'type' => 'number',
                'placeholder' => Yii::t('app', 'Language ID (optional)'),
            ]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'avatar_url')
            ->textInput([
                'maxlength' => true,
                'placeholder' => Yii::t('app', 'Avatar URL (optional)'),
            ]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <?= $form->field($model, 'password')
            ->passwordInput([
                    'maxlength' => true,
                    'autocomplete' => 'new-password',
                    'placeholder' => Yii::t('app', 'Password'),
            ])
            ->hint($isUpdate ? Yii::t('app', 'Leave blank to keep current password') : null
        ) ?>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'password_repeat')
            ->passwordInput([
                'maxlength' => true,
                'autocomplete' => 'new-password',
                'placeholder' => Yii::t('app', 'Repeat password'),
            ]
        ) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-10">
        <div class="mt-4 d-flex justify-content-end gap-2">
            <?= \yii\bootstrap5\Html::submitButton(
                    $isUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Create'),
                    ['class' => $isUpdate ? 'btn btn-primary' : 'btn btn-success']
            ) ?>
        </div>
    </div>
</div>