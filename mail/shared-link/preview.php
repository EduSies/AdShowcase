<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $title */
/** @var string $format */
/** @var string $agency */
/** @var string $qrSrc */
/** @var string $url */

$grayText = 'color: #6c757d; font-family: Helvetica, Arial, sans-serif;';
$orangeText = 'color: #FF6600; font-family: Helvetica, Arial, sans-serif;';

?>

<div style="text-align: center;">
    <p style="<?= $grayText ?> font-weight: bold; margin-bottom: 5px; font-size: 14px;">
        <?= Yii::t('app', 'Hey! AdShowcase has shared a preview with you') ?>
    </p>

    <div style="margin: 20px 0px auto; padding: 10px; background: #f8f9fa; display: inline-block; border-radius: 8px;">
        <img src="<?= $qrSrc ?>" alt="QR Code" width="200" height="200" style="display: block;">
    </div>

    <p style="<?= $grayText ?> font-size: 12px; margin-bottom: 5px; margin-top: 0px;">
        <?= Yii::t('app', 'Scan the QR code or click the button below') ?>
    </p>

    <p style="<?= $grayText ?> font-size: 14px; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px;margin-top: 35px;">
        <?= Html::encode($format) ?>
    </p>

    <h2 style="<?= $orangeText ?> margin: 10px 0; font-size: 24px;">
        <?= Html::encode($title) ?>
    </h2>

    <p style="<?= $grayText ?> font-size: 14px; margin-bottom: 20px;">
        <?= Html::encode($agency) ?>
    </p>

    <a href="<?= Html::encode($url) ?>" style="margin-bottom: 15px;background-color: #2563eb; color: #ffffff; padding: 14px 35px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 14px; display: inline-block; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);">
        <?= Yii::t('app', 'Click here to see the preview') ?>
    </a>
</div>