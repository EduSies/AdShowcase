<?php

/** @var array $listsFavorites */
/** @var string $creativeHash */

$defaultList = null;
$customLists = [];
$defaultName = Yii::t('app', 'Your favorites');

foreach ($listsFavorites as $list) {
    if (empty($list['hash']) || $list['name'] === $defaultName) {
        $defaultList = $list;
    } else {
        $customLists[] = $list;
    }
}

?>

<div class="d-flex flex-column gap-2">
    <?php if ($defaultList): ?>
        <?= $this->render('_list-item-row', [
            'list' => $defaultList,
            'creativeHash' => $creativeHash,
            'isDefault' => true
        ]) ?>
    <?php endif; ?>

    <?php if (!empty($customLists)): ?>
        <div class="text-muted ms-2" style="font-size: 0.9rem;">
            <?= Yii::t('app', 'My lists') ?>
        </div>

        <?php foreach ($customLists as $list): ?>
            <?= $this->render('_list-item-row', [
                'list' => $list,
                'creativeHash' => $creativeHash,
                'isDefault' => false
            ]) ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>