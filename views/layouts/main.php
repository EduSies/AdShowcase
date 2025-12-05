<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Icon;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();

$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerMetaTag(['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge']);

$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.svg')]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css2?family=Allerta+Stencil']);

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
<body class="d-flex flex-column min-vh-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md bg-white'],
        ]);

        // Left side navigation (Catalog, Back Office)
        $leftItems = [
            [
                'label' => Icon::widget([
                        'icon' => 'bi-collection-play',
                        'size' => Icon::SIZE_24,
                        'options' => ['class' => 'flex-shrink-0'],
                    ]) .
                    Html::tag('span', Yii::t('app', 'Catalog'), ['class' => 'ms-2']),
                'url' => ['/catalog/index'],
            ],
        ];

        if (!Yii::$app->user->isGuest && Yii::$app->user->can('backoffice.access')) {
            $leftItems[] = [
                'label' => Icon::widget([
                        'icon' => 'bi-gear',
                        'size' => Icon::SIZE_24,
                        'options' => ['class' => 'flex-shrink-0'],
                    ]) .
                    Html::tag('span', Yii::t('app', 'Back Office'), ['class' => 'ms-2']),
                'url' => '#',
                'linkOptions' => [
                    'data-bs-toggle' => 'offcanvas',
                    'data-bs-target' => '#offcanvasBackOffice',
                    'aria-controls' => 'offcanvasBackOffice',
                ],
            ];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav mx-auto'],
            'encodeLabels' => false,
            'items' => $leftItems,
        ]);

        // Right side navigation (Logout)
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                '<li class="nav-item">'
                    . Html::beginForm(['/auth/logout'], 'post', ['class' => 'd-inline'])
                    . Html::submitButton(
                        Icon::widget([
                            'icon' => 'bi-box-arrow-right',
                            'size' => Icon::SIZE_24,
                            'options' => ['class' => 'flex-shrink-0 me-1'],
                        ]) .
                        Html::tag(
                            'span',
                            Html::encode(
                                Yii::t(
                                    'app',
                                    'Logout ({username})',
                                    ['username' => Yii::$app->user->identity->username]
                                )
                            ),
                            ['class' => 'align-middle']
                        ),
                        [
                            'class' => 'btn btn-sm btn-outline-danger logout d-inline-flex align-items-center gap-1',
                            'encode' => false,
                        ]
                    )
                    . Html::endForm() .
                '</li>',
            ],
        ]);

        NavBar::end();
    ?>
    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('backoffice.access')): ?>
        <?= $this->render('partials/_off-canvas-sections', [
            'sections' => $this->context->getSectionsMenu()
        ]) ?>
    <?php endif; ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-white">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; AdShowcase <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>