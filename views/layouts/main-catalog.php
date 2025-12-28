<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\helpers\LangHelper;
use app\widgets\Icon;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();

$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerMetaTag(['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge']);

$this->registerMetaTag(['property' => 'og:image', 'content' => Url::to('@web/images/ad-showcase-og-image.png', true)]);
$this->registerMetaTag(['property' => 'og:image:width', 'content' => '1200']);
$this->registerMetaTag(['property' => 'og:image:height', 'content' => '630']);

$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.svg')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css2?family=Allerta+Stencil']);

// Manejo de alertas (Flash Messages)
if (Yii::$app->session->hasFlash('success')) {
    $this->registerJs("swalSuccess('" . Yii::$app->session->getFlash('success') . "');");
}

if (Yii::$app->session->hasFlash('error')) {
    $this->registerJs("swalDanger('" . Yii::$app->session->getFlash('error') . "');");
}

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="min-vh-100" data-theme="light">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column min-vh-100 bg-light">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['id' => 'nav', 'class' => 'navbar-expand-lg bg-white fixed-top shadow-sm', 'style' => 'min-height: 72px;'],
            'innerContainerOptions' => ['class' => 'container-fluid mx-4 mx-sm-5'],
            'brandOptions' => ['style' => 'font-size: 30px;'],
            'collapseOptions' => ['class' => 'w-100 align-items-center justify-content-end'],
        ]);

        // Left side navigation (Creative Catalog, Back Office)
        $leftItems = [
            [
                'label' => Icon::widget([
                    'icon' => 'bi-search',
                    'size' => Icon::SIZE_24,
                    'options' => ['id' => 'btnSearch', 'class' => 'flex-shrink-0'],
                ]),
                'url' => '#',
                'encode' => false,
            ],
        ];

        if (!Yii::$app->user->isGuest && Yii::$app->user->can('backoffice.access')) {
            $leftItems[] = [
                'label' => Icon::widget([
                    'icon' => 'bi-gear',
                    'size' => Icon::SIZE_24,
                    'options' => ['class' => 'flex-shrink-0'],
                ]),
                'url' => '#',
                'encode' => false,
                'linkOptions' => [
                    'data-bs-toggle' => 'offcanvas',
                    'data-bs-target' => '#offcanvasBackOffice',
                    'aria-controls' => 'offcanvasBackOffice',
                ],
            ];
        }

        // Preparamos los datos del usuario actual
        $identity = Yii::$app->user->identity;

        // Asumimos que tienes una propiedad avatar_url. Si es null, usamos una imagen por defecto.
        $avatarUrl = $identity->avatar_url ?? Yii::getAlias('@web/images/default-avatar.png');

        // Nombre completo y Rol (Ajusta 'type' si tu campo de rol se llama diferente)
        $fullName = Html::encode($identity->name . ' ' . $identity->surname);
        $username = Html::encode($identity->username);
        $roleName = Html::encode($identity->type ?? 'User'); // O usa RBAC si es necesario

        // Right side navigation (Logout)
        echo Nav::widget([
            'options' => ['id' => 'menu-right', 'class' => 'navbar-nav gap-2 align-items-center justify-content-end'],
            'items' => array_merge(
                [
                    '<li class="nav-item d-flex align-items-center px-2">' .
                        $this->render('partials/_input-search', [
                            'filterSearch' => $filterSearch ?? ''
                        ]) .
                    '</li>'
                ],
                $leftItems, [
                [
                    'label' => Icon::widget([
                        'icon' => 'bi-globe',
                        'size' => Icon::SIZE_24,
                        'options' => ['class' => 'flex-shrink-0 me-1'],
                    ]),
                    'encode' => false,
                    'dropdownOptions' => ['class' => 'dropdown-menu-end mt-2 shadow-lg'],
                    'items' => LangHelper::getLanguageItems(),
                    'linkOptions' => [
                        'id' => 'langDropdown',
                        'class' => 'nav-link',
                        'title' => Yii::t('app', 'Select language'),
                    ],
                ],
                [
                    // Imagen del Avatar
                    'label' => Html::img($avatarUrl, [
                        'class' => 'rounded-circle object-fit-cover border shadow-sm',
                        'style' => 'width: 40px; height: 40px;', // Tamaño fijo para el círculo
                        'alt' => $username,
                    ]),
                    'encode' => false, // Permite que se vea el icono

                    // Opciones del menú desplegable
                    'dropdownOptions' => [
                        'class' => 'dropdown-menu-end mt-2 shadow-lg',
                        'style' => 'min-width: 220px;',
                    ],
                    'linkOptions' => [
                        'class' => 'nav-link p-0 ms-2',
                        'id' => 'userDropdown',
                        'role' => 'button',
                        'data-bs-toggle' => 'dropdown',
                        'aria-expanded' => 'false',
                        'title' => Yii::t('app', 'Click to open user dropdown'),
                    ],

                    // El Contenido del Dropdown
                    'items' => [
                        Html::tag('div',
                            Html::tag('div', $fullName, ['class' => 'fw-bold text-dark']) .
                            Html::tag('div', '@' . $username, ['class' => 'text-muted small']) .
                            Html::tag('div', $roleName, ['class' => 'badge rounded-pill text-bg-secondary mt-3']),
                            ['class' => 'px-4 py-3']
                        ),
                        Html::tag('hr','', ['class' => 'm-0 mx-4']),
                        [
                            'label' => Icon::widget([
                                    'icon' => 'bi-box-arrow-right',
                                    'size' => Icon::SIZE_16,
                                    'options' => ['class' => 'flex-shrink-0'],
                                ]) . Html::tag('span', Yii::t('app', 'Logout'), ['class' => 'ms-2']),
                            'url' => ['/auth/logout'],
                            'linkOptions' => [
                                'data-method' => 'post',
                                'class' => 'dropdown-item text-danger py-2 px-4 d-flex align-items-center',
                                'id' => 'logout',
                            ],
                            'encode' => false, // Permite que se vea el icono
                        ],
                    ],
                ],
            ]),
        ]);

        NavBar::end();

        ?>

        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('backoffice.access')): ?>
            <?= $this->render('partials/_off-canvas-sections', [
                'sections' => $this->context->getSectionsMenu()
            ]) ?>
        <?php endif; ?>
    </header>

    <div class="position-fixed min-vh-100 min-vw-100" style="background: linear-gradient(141deg, #F3F5FB 14.05%, #FFDCCC 139.54%);"></div>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container-fluid p-0">
            <?= $content ?>
        </div>
    </main>

    <template id="spinner-template">
        <div class="d-flex justify-content-center align-items-center w-100">
            <div class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                <span class="visually-hidden"><?= Yii::t('app', 'Loading') ?>...</span>
            </div>
        </div>
    </template>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>