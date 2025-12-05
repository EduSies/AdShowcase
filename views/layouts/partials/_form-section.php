<?php

/** @var array $indexRoute */

use app\widgets\Icon;
use yii\bootstrap5\Html;

?>

<div class="mb-4">
    <?= Html::a(
            Icon::widget([
                'icon' => 'bi-arrow-left',
                'size' => Icon::SIZE_24,
                'options' => ['class' => 'flex-shrink-0'],
            ]) .
            Html::tag('span', Yii::t('app', 'Back to list'), ['class' => 'ms-2']),
            $indexRoute ?? null,
            ['class' => 'btn btn-outline-secondary']
    ) ?>
</div>

<section class="form row">
    <div class="col-xl-3 form-title">
        <h5><?= $title ?? '' ?></h5>
    </div>

    <div class="<?= $class ?? 'col-xl-9' ?>">
        <?= $content ?? '' ?>
    </div>
</section>