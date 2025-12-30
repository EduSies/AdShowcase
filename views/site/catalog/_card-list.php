<?php

/** @var yii\web\View $this */
/** @var array $creatives */
/** @var array $listsFavorites */
/** @var string $routeButtonSearch */
/** @var bool $isFavorites */
/** @var bool $isFavoritesDetail */

?>

<?php if (empty($creatives)): ?>

    <?= $this->render('@adshowcase.layouts/partials/_empty-search', [
        'routeButtonSearch' => $routeButtonSearch,
    ]) ?>

<?php else: ?>

    <?php
        foreach ($creatives as $creative):
        $classColumn = ($isFavoritesDetail ? 'col-lg-4 col-md-6 col-sm-12 col-12 cards' : 'col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12 cards');
    ?>
        <div class="<?= $classColumn ?>">
            <?= $this->render('_item-creative', [
                'creative' => $creative,
                'listsFavorites' => $listsFavorites
            ]) ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>