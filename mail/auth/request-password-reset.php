<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var string $url */

$grayText = 'color: #6c757d; font-family: Helvetica, Arial, sans-serif;';
$orangeText = 'color: #FF6600; font-family: Helvetica, Arial, sans-serif;';

?>

<div style="text-align: center;">
    <h2 style="<?= $orangeText ?> margin: 10px 0; font-size: 24px;">
        <?= Yii::t('app', 'Hello, {name}', ['name' => Html::encode($user->name)]) ?>
    </h2>

    <p style="<?= $grayText ?> font-size: 16px; margin-bottom: 20px;">
        <?= Yii::t('app', 'Follow the link below to reset your password:') ?>
    </p>

    <a href="<?= Html::encode($url) ?>" style="background-color: #2563eb; color: #ffffff; padding: 14px 35px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 14px; display: inline-block; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2); margin-bottom: 30px;">
        <?= Yii::t('app', 'Reset Password') ?>
    </a>

    <p style="<?= $grayText ?> font-size: 12px; margin-top: 20px;">
        <?= Yii::t('app', 'If you did not make this request, please ignore this email.') ?>
    </p>
</div>