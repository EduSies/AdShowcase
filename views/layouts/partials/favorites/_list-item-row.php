<?php

use yii\helpers\Html;
use app\widgets\Icon;

/** @var array $list Datos de la lista (hash, name, image, itemsHashes) */
/** @var string $creativeHash Hash de la creatividad actual */
/** @var bool $isDefault Si es la lista por defecto */

// Calculamos si está añadido
$isAdded = in_array($creativeHash, $list['itemsHashes'] ?? []);

// Helper para renderizar el botón (Reutilizado o copiado aquí si prefieres encapsularlo)
$renderBtn = function($listHash, $isAdded, $isCustom) {
    $contentAdd = Html::tag('span',
        Yii::t('app', 'Add') . ' ' . Icon::widget(['icon' => 'bi-plus-lg']),
        ['class' => 'state-add ' . ($isAdded ? 'd-none' : '')]
    );

    $contentAdded = Html::tag('span',
        Yii::t('app', 'Added') . ' ' . Icon::widget(['icon' => 'bi-check2']),
        ['class' => 'state-added ' . (!$isAdded ? 'd-none' : '')]
    );

    $btnClass = $isAdded ? 'btn-success' : ($isCustom ? 'btn-light color-main-2 border-color-2' : 'btn-primary');

    return Html::button($contentAdd . $contentAdded, [
        'class' => 'btn ' . $btnClass . ' btn-sm rounded-pill px-3 d-flex align-items-center gap-1 toggle-list-btn',
        'data-list-hash' => $listHash,
        'data-action' => $isAdded ? 'remove' : 'add',
        'type' => 'button'
    ]);
};

$image = $list['images'][0] ?? null;

?>

<div class="d-flex justify-content-between align-items-center p-2 rounded hover-bg-light transition-base list-favorite <?= ($isDefault) ? 'mb-3' : '' ?>">
    <div class="d-flex align-items-center gap-3 overflow-hidden">
        <div class="rounded d-flex align-items-center justify-content-center text-white bg-main-1 shadow-sm"
             style="width: 50px; height: 50px; min-width: 50px;"
        >
            <?php if (!empty($image)): ?>
                <?= Html::img($image, ['class' => 'w-100 h-100 object-fit-cover']) ?>
            <?php else: ?>
                <?= Icon::widget(['icon' => 'bi-list-ul', 'size' => Icon::SIZE_24]) ?>
            <?php endif; ?>
        </div>
        <h6 class="mb-0 fw-bold text-truncate" style="max-width: 140px;" title="<?= Html::encode($list['name']) ?>">
            <?= Html::encode($list['name']) ?>
        </h6>
    </div>

    <?= $renderBtn($list['hash'], $isAdded, !$isDefault) ?>
</div>