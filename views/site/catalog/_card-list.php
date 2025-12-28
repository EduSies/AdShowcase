<?php

/** @var yii\web\View $this */
/** @var array $creatives */
/** @var array $listsFavorites */

use app\widgets\Icon;
use yii\helpers\Url;

?>

<?php if (empty($creatives)): ?>

    <div class="col-12">
        <div class="d-flex flex-column justify-content-center align-items-center py-5 text-center fade-in-card">
            <div class="d-flex align-items-center justify-content-center mb-5">
                <?= Icon::widget([
                    'icon' => 'bi-search',
                    'options' => [
                        'class' => 'text-secondary opacity-50',
                    ],
                    'size' => Icon::SIZE_80
                ]) ?>
            </div>

            <h4 class="fw-bold text-dark mb-2"><?= Yii::t('app', 'No results found') ?></h4>
            <p class="text-muted mb-5" style="max-width: 550px;">
                <?= Yii::t('app', 'We couldn\'t find any creatives matching your filters. Try searching for a different term or clearing some filters.') ?>
            </p>

            <button class="btn btn-outline-primary rounded-pill px-4" onclick="window.location.href='<?= Url::to(['site/catalog']) ?>'">
                <?= Yii::t('app', 'Clear all filters') ?>
            </button>
        </div>
    </div>

<?php else: ?>

    <?php foreach ($creatives as $creative): ?>
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4 fade-in-card">
            <?= $this->render('_item-creative', [
                'creative' => $creative,
                'listsFavorites' => $listsFavorites
            ]) ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>