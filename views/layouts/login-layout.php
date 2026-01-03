<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\helpers\LangHelper;
use app\widgets\Icon;
use yii\bootstrap5\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

$this->title = ArrayHelper::getValue($_ENV, 'APP_NAME');

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

if (Yii::$app->session->hasFlash('success')) {
    $this->registerJs("swalSuccess('" . Yii::$app->session->getFlash('success') . "');");
}

if (Yii::$app->session->hasFlash('error')) {
    $this->registerJs("swalDanger('" . Yii::$app->session->getFlash('error') . "');");
}

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="min-vh-100">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="login-layout d-flex flex-column min-vh-100">
    <?php $this->beginBody() ?>
    <div class="position-fixed min-vh-100 min-vw-100 bg-back-main"></div>
    <div class="position-absolute top-0 end-0 my-3 mx-4" style="z-index: 1050;">
        <?= Nav::widget([
            'options' => ['class' => 'nav-pills navbar-nav gap-2 text-secondary'],
            'items' => [
                [
                    'label' => Icon::widget([
                        'icon' => 'bi-globe',
                        'size' => Icon::SIZE_24,
                        'options' => ['class' => 'flex-shrink-0'],
                    ]),
                    'encode' => false,
                    'dropdownOptions' => ['class' => 'mt-2 shadow-lg dropdown-menu-end border-0'],
                    'items' => LangHelper::getLanguageItems(),
                    'linkOptions' => [
                        'id' => 'langDropdown',
                        'class' => 'nav-link',
                        'title' => Yii::t('app', 'Select language'),
                    ],
                ],
            ],
        ]) ?>
    </div>
    <main id="main" class="flex-shrink-0 position-relative" role="main">
        <div class="container d-flex flex-column align-items-center">
            <h1 class="logo-adshowcase mb-5">
                <?= Html::a(
                    ArrayHelper::getValue($_ENV, 'APP_NAME'),
                    ['/'],
                    ['class' => 'text-decoration-none text-reset']
                ) ?>
            </h1>
            <?= $content ?>
        </div>
    </main>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>