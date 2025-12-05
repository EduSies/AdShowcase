<?php

use yii\helpers\Html;

/** @var array $sections */

?>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasBackOffice"
     aria-labelledby="offcanvasBackOfficeLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasBackOfficeLabel">
            <?= Yii::t('app', 'Back Office') ?>
        </h5>
        <?= Html::button('', [
            'type' => 'button',
            'class' => 'btn-close text-reset',
            'data-bs-dismiss' => 'offcanvas',
            'aria-label' => Yii::t('app', 'Close'),
        ]) ?>
    </div>
    <div class="offcanvas-body">

        <?php if (!empty($sections)): ?>
            <?php foreach ($sections as $section): ?>
                <?php
                    $sectionLabel = Yii::t('app', $section['label'] ?? '');
                    $items = $section['items'] ?? [];

                    // Keep only visible items (respecting 'visible' flag computed in getSectionsMenu)
                    $visibleItems = array_filter($items, static function (array $item): bool {
                        return !isset($item['visible']) || $item['visible'];
                    });

                    // If there are no visible items, skip this section entirely
                    if (empty($visibleItems)) {
                        continue;
                    }
                ?>
                <div class="mb-4">
                    <h6 class="text-uppercase small fw-semibold text-muted mb-2">
                        <?= Html::encode($sectionLabel) ?>
                    </h6>
                    <div class="list-group list-group-flush">
                        <?php foreach ($visibleItems as $item): ?>
                            <?php
                                $url = $item['url'] ?? '#';
                                $route = Yii::$app->controller->route ?? '';
                                $pattern = $item['activePattern'] ?? null;

                                $isActive = false;
                                if (is_string($pattern) && $pattern !== '') {
                                    $isActive = (bool)preg_match($pattern, $route);
                                }

                                echo $this->render('_off-canvas-item', [
                                    'label' => $item['label'] ?? '',
                                    'icon' => $item['icon'] ?? null,
                                    'url' => $url,
                                    'isActive' => $isActive,
                                ]);
                            ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>