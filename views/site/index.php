<?php

/** @var yii\web\View $this */
/** @var string $title */

/** @var array $sections */

use app\widgets\Icon;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = $title;

?>

<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4 logo-adshowcase-dashboard"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="body-content">

        <?php if (!empty($sections)): ?>
            <?php foreach ($sections as $section): ?>
                <?php
                $sectionLabel = Yii::t('app', $section['label'] ?? '');
                $items        = $section['items'] ?? [];

                // Keep only visible items
                $visibleItems = array_filter($items, static function (array $item): bool {
                    return !isset($item['visible']) || $item['visible'];
                });

                // Skip section if all items are hidden
                if (empty($visibleItems)) {
                    continue;
                }
                ?>
                <div class="mb-5">
                    <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">
                        <?= Html::encode($sectionLabel) ?>
                    </h2>

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                        <?php foreach ($visibleItems as $item): ?>
                            <?php
                                $url = $item['url'] ?? '#';
                                $label = Yii::t('app', $item['label'] ?? '');
                                $icon = $item['icon'] ?? null;
                                $descKey = $item['description'] ?? null;
                                $route = Yii::$app->controller->route ?? '';
                                $pattern = $item['activePattern'] ?? null;
                                $isActive = is_string($pattern) && $pattern !== '' ? (bool)preg_match($pattern, $route) : false;
                            ?>
                            <div class="col">
                                <a href="<?= Url::to($url) ?>" class="text-decoration-none">
                                    <div class="card h-100 shadow-sm border-1 <?= $isActive ? 'border-primary' : '' ?>">
                                        <?php if ($icon): ?>
                                            <div class="mt-3 d-flex justify-content-center">
                                                <?= Icon::widget([
                                                    'icon'    => $icon,
                                                    'size'    => Icon::SIZE_56,
                                                    'options' => ['class' => 'text-muted'],
                                                ]) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-body text-center">
                                            <h5 class="card-title mb-2 text-muted font-adshowcase">
                                                <?= Html::encode($label) ?>
                                            </h5>
                                            <?php if (!empty($descKey)): ?>
                                                <p class="card-text text-muted small mb-0">
                                                    <?= Html::encode(Yii::t('app', $descKey)) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">
                <?= Yii::t('app', 'No dashboard sections available.') ?>
            </p>
        <?php endif; ?>

    </div>
</div>