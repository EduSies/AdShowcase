<?php

use yii\helpers\Html;

?>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasBackOffice" aria-labelledby="offcanvasBackOfficeLabel">
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

        <!-- Creatives section -->
        <div class="mb-4">
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">
                <?= Yii::t('app', 'Creatives') ?>
            </h6>
            <div class="list-group list-group-flush">
                <?= Html::a(
                        Yii::t('app', 'Creatives'),
                        ['back-office/creatives'],
                        ['class' => 'list-group-item list-group-item-action']
                ) ?>
            </div>
        </div>

        <!-- Taxonomies section -->
        <div class="mb-4">
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">
                <?= Yii::t('app', 'Taxonomies') ?>
            </h6>
            <div class="list-group list-group-flush">
                <?= Html::a(
                    Yii::t('app', 'Brands'),
                    ['back-office/brands'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>

                <?= Html::a(
                    Yii::t('app', 'Agencies'),
                    ['back-office/agencies'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>

                <?= Html::a(
                    Yii::t('app', 'Devices'),
                    ['back-office/devices'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>

                <?= Html::a(
                    Yii::t('app', 'Formats'),
                    ['back-office/formats'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>

                <?= Html::a(
                    Yii::t('app', 'Countries'),
                    ['back-office/countries'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>

                <?= Html::a(
                    Yii::t('app', 'Products'),
                    ['back-office/products'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>

                <?= Html::a(
                    Yii::t('app', 'Sales Types'),
                    ['back-office/sales-types'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>
            </div>
        </div>

        <!-- Users section -->
        <div class="mb-4">
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">
                <?= Yii::t('app', 'Users') ?>
            </h6>
            <div class="list-group list-group-flush">
                <?= Html::a(
                    Yii::t('app', 'Users'),
                    ['back-office/users'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>
            </div>
        </div>

        <!-- Shared links section -->
        <div class="mb-4">
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">
                <?= Yii::t('app', 'Shared links') ?>
            </h6>
            <div class="list-group list-group-flush">
                <?= Html::a(
                    Yii::t('app', 'Shared links'),
                    ['back-office/shared-link'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>
            </div>
        </div>

        <!-- Audit & Settings section -->
        <div class="mb-4">
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">
                <?= Yii::t('app', 'Audit & settings') ?>
            </h6>
            <div class="list-group list-group-flush">
                <?= Html::a(
                    Yii::t('app', 'Audit log'),
                    ['back-office/audit-log'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>

                <?= Html::a(
                    Yii::t('app', 'Settings'),
                    ['back-office/settings'],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>
            </div>
        </div>

    </div>
</div>