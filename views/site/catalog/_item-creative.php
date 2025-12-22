<?php

/** @var array $creative */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\Flag; // Asegúrate de tener tu widget de banderas importado
use app\models\UserCreativeList; // Para la lógica de favoritos

// 1. Preparar Datos
$detailUrl = Url::to(['creative/view', 'hash' => $creative['hash']]);
$brandName = !empty($creative['brand']) ? $creative['brand']['name'] : 'Brand';
$agencyName = !empty($creative['agency']) ? $creative['agency']['name'] : 'Agency';
$countryCode = !empty($creative['country']) ? strtolower($creative['country']['iso']) : 'xx';

// Icono dispositivo (El de la imagen parece un monitor con peana)
$deviceIcon = match ($creative['device_id']) {
    1 => 'bi-display',       // Desktop
    2 => 'bi-phone',         // Mobile
    3 => 'bi-tablet',        // Tablet
    default => 'bi-display',
};

// Obtener listas del usuario para el dropdown de favoritos
$userLists = []; //!Yii::$app->user->isGuest ? UserCreativeList::find()->where(['user_id' => Yii::$app->user->id])->all() : [];
?>

<div class="card h-100 border-0 shadow-sm creative-card">

    <div class="position-relative">

        <div class="ratio ratio-16x9">
            <img src="<?= $creative['url_thumbnail'] ?>"
                 class="card-img-top object-fit-cover"
                 alt="<?= Html::encode($creative['title']) ?>">
        </div>

        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0) 30%); pointer-events: none;">
        </div>

        <div class="position-absolute top-0 end-0 p-2 dropdown">
            <button class="btn btn-link text-white p-0 shadow-none"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    id="favDropdown-<?= $creative['hash'] ?>">
                <i class="bi bi-star" style="font-size: 1.3rem; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.4));"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" aria-labelledby="favDropdown-<?= $creative['hash'] ?>" style="min-width: 260px; z-index: 1050;">
                <div class="p-2 border-top bg-light">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control new-list-input" placeholder="<?= Yii::t('app', 'New list...') ?>">
                        <button class="btn btn-primary btn-create-list" type="button" data-creative-hash="<?= $creative['hash'] ?>"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="position-absolute start-50 translate-middle-x" style="bottom: 0; transform: translate(-50%, 50%); z-index: 5;">
            <?= Flag::widget([
                    'tag' => 'span',
                    'country' => $countryCode,
                    'options' => [
                            'class' => 'shadow-sm border border-2 border-white d-block',
                            'style' => 'width: 28px; height: 20px;'
                    ]
            ]) ?>
        </div>
    </div>

    <div class="card-body text-center pt-4 d-flex flex-column align-items-center">

        <h6 class="card-title fw-bold text-uppercase text-dark mb-1" style="font-size: 0.85rem; letter-spacing: 0.3px;">
            <?= Html::encode($creative['title']) ?>
        </h6>

        <div class="small mb-3" style="font-size: 0.8rem;">
            <a href="#" class="text-decoration-none text-primary fw-medium"><?= Html::encode($brandName) ?></a>
            <span class="text-muted mx-1">&gt;</span>
            <span class="text-primary"><?= Html::encode($agencyName) ?></span>
        </div>

        <div class="mt-auto text-secondary opacity-50">
            <i class="bi <?= $deviceIcon ?>" style="font-size: 1.5rem;"></i>
        </div>

        <a href="<?= $detailUrl ?>" class="stretched-link position-absolute w-100 h-100 start-0 top-0" style="z-index: 1;"></a>
    </div>
</div>