<?php

/** @var yii\web\View $this */
/** @var array $creative */
/** @var string $vastXml */
/** @var string $authorName */

use app\widgets\Icon;
use yii\helpers\Html;

\app\assets\AppAssetSiteMockup::register($this);

date_default_timezone_set("Europe/Madrid");

$this->title = Yii::t('app', 'TheNewTech');

$brand = Yii::t('app', 'AdShowcase');

$currentDate = Yii::$app->formatter->asDate(time(), 'full');

?>

    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head(); ?>
    </head>

    <body id="body_wrap" class="desktopCreative">
    <?php $this->beginBody(); ?>

    <header>
        <div class="d-md-block d-none">
            <div class="date d-inline-flex align-items-center justify-content-start gap-2">
                <?= Icon::widget(['icon' => 'bi-globe2', 'size' => Icon::SIZE_16, 'options' => ['class' => 'color-main-1']]) ?>
                <div class="small"><?= $currentDate ?></div>
            </div>

            <div class="d-inline-flex align-items-center justify-content-end gap-3 float-end">
                <?= Icon::widget(['icon' => 'bi-search', 'size' => Icon::SIZE_16]) ?>
                <div class="small"><?= Yii::t('app', 'Sign in') ?></div>
                <button class="btn btn-primary btn-sm bg-main-1 small rounded-pill border-0 px-3" style="pointer-events: none;">
                    <?= Yii::t('app', 'Subscribe') ?>
                </button>
            </div>

            <div class="logo d-flex justify-content-center position-relative">
                <?= Html::img('@web/images/site-mockup/logo.svg', ["alt" => "logo", "style" => "height: 40px;"]) ?>
            </div>

            <div class="menu small d-flex justify-content-center gap-4">
                <div><?= Yii::t('app', 'Home') ?></div>
                <div><?= Yii::t('app', 'News') ?></div>
                <div><?= Yii::t('app', 'Market') ?></div>
                <div><?= Yii::t('app', 'Economy') ?></div>
                <div><?= Yii::t('app', 'Opinion') ?></div>
                <div><?= Yii::t('app', 'Finance') ?></div>
                <div><?= Yii::t('app', 'Companies') ?></div>
                <div><?= Yii::t('app', 'Environment') ?></div>
                <div><?= Yii::t('app', 'Podcasts') ?></div>
                <div><?= Yii::t('app', 'Video') ?></div>
            </div>

            <div class="d-flex justify-content-center pt-1">
                <div class="line-menu"></div>
            </div>
        </div>

        <div id="header-mobile" class="d-md-none d-flex">
            <div>
                <?= Icon::widget(['icon' => 'bi-list', 'size' => Icon::SIZE_24]) ?>
            </div>
            <div class="d-flex flex-column align-items-center gap-2 pt-2">
                <?= yii\helpers\Html::img('@web/images/site-mockup/logo.svg', ["alt" => "logo", "style" => "height: 30px;"]) ?>
                <div class="small"><?= $currentDate ?></div>
            </div>
            <div>
                <div><?= Icon::widget(['icon' => 'bi-person', 'size' => Icon::SIZE_24]) ?></div>
            </div>
        </div>
    </header>

    <div id="main" class="col-12 d-flex">
        <div class="container">
            <div class="row">
                <div id="dot" class="col-md-1 pb-2 d-md-block d-flex align-items-center gap-2 mt-md-4 mt-0 pt-md-2 pt-0">
                    <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/dot.png"), ["alt" => "dot"]) ?>
                    <div class="d-md-none d-grid "><?= Yii::t('app', 'Market') ?></div>
                </div>

                <div id="content" class="col-md-7 mb-md-5 pb-md-2">
                    <div class="title mb-4 pb-md-1">
                        <div class="d-none d-md-block small color-main-1 mb-1"><?= Yii::t('app', 'Market') ?></div>
                        <h1 class="mb-2"><?= Yii::t('app', 'Pioneers in Digital Advertising Technology') ?></h1>
                        <h4 class="fw-light opacity-50"><?= Yii::t('app', 'Merging the Digital with the Tangible in the World of Advertising') ?></h4>
                    </div>
                    <div class="text1">
                        <div>
                            <?= Yii::t('app', 'In a digital landscape where every corner seems to be flooded with messages and adverts, {brand} rises prominently, setting a standard of excellence in the vast universe of advertising. With a remarkable presence in regions such as EMEA, US, and Latam, the authenticity and excellence of our team set us apart. They are professionals who are not only highly skilled, but also truly passionate, always ready to innovate and revitalise the advertising landscape.', ['brand' => '<strong>'.$brand.'</strong>']) ?>
                        </div>
                        <div>
                            <?= Yii::t('app', 'Our approach, effectively melding the context of online content leveraging state-of-the-art machine learning technologies with the tangible world of the user, makes {brand} stand out. This blend results in an authentic and lasting connection between brands and consumers. And yes, we achieve this feat with deep respect for user privacy, thanks to a cookie-free environment, placing us at the forefront of innovative content segmentation and delivery.', ['brand' => '<strong>'.$brand.'</strong>']) ?>
                        </div>
                    </div>

                    <div class="adshowcase-player-preview my-4"></div>

                    <?php if (!empty($vastXml)): ?>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const vastContent = <?= json_encode($vastXml) ?>;
                                const container = document.querySelector('.adshowcase-player-preview');

                                if (!vastContent || !container) return;

                                try {
                                    const parser = new DOMParser();
                                    const xmlDoc = parser.parseFromString(vastContent, "text/xml");

                                    // Elementos para Video
                                    const mediaFile = xmlDoc.querySelector('MediaFile');
                                    // Elementos para Imagen
                                    const staticResource = xmlDoc.querySelector('StaticResource');

                                    let sourceUrl = '';
                                    let clickUrl = '#';
                                    let element = null;

                                    // --- CASO 1: VÍDEO (Linear) ---
                                    if (mediaFile) {
                                        sourceUrl = mediaFile.textContent.trim();
                                        const type = mediaFile.getAttribute('type') || 'video/mp4';

                                        // ClickThrough está dentro de VideoClicks
                                        const clickNode = xmlDoc.querySelector('ClickThrough');
                                        if (clickNode) clickUrl = clickNode.textContent.trim();

                                        // Crear Video
                                        element = document.createElement('video');
                                        element.src = sourceUrl;
                                        element.type = type;
                                        element.autoplay = true;
                                        element.loop = true;
                                        element.muted = true;
                                        element.playsInline = true;
                                        element.controls = false;
                                    }
                                    // --- CASO 2: IMAGEN (NonLinear) ---
                                    else if (staticResource) {
                                        sourceUrl = staticResource.textContent.trim();

                                        // NonLinearClickThrough está dentro de NonLinear
                                        const clickNode = xmlDoc.querySelector('NonLinearClickThrough');
                                        if (clickNode) clickUrl = clickNode.textContent.trim();

                                        // Crear Imagen
                                        element = document.createElement('img');
                                        element.src = sourceUrl;
                                        element.className = 'img-fluid'; // Bootstrap class
                                    }

                                    // --- RENDERIZADO COMÚN ---
                                    if (element) {
                                        // Estilos comunes
                                        element.style.width = '100%';
                                        element.style.display = 'block';
                                        element.style.objectFit = 'cover';

                                        // Crear el enlace envolvente
                                        const link = document.createElement('a');
                                        link.href = clickUrl;
                                        link.target = '_blank';
                                        link.style.display = 'block';
                                        link.style.textDecoration = 'none';

                                        link.appendChild(element);

                                        container.innerHTML = '';
                                        container.appendChild(link);
                                    }

                                } catch (e) {
                                    console.error("Error procesando VAST:", e);
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <div class="text2">
                        <div>
                            <?= Yii::t('app', 'But the innovation doesn\'t stop there. {brand} redefines creativity. With native integration of dynamic creativity (DCO) and advanced rich-media capabilities, it provides advertisers with the essential tools to forge emotional ties that not only capture immediate attention but also leave a lasting impression, ensuring the right message lands at just the right moment.', ['brand' => '<strong>'.$brand.'</strong>']) ?>
                        </div>
                        <div>
                            <?= Yii::t('app', 'In terms of data analysis, {brand} is not just pioneering, it\'s revolutionary. It not only transforms vast data sets into valuable insights that drive effective decisions, but by blending metrics derived from all the previously mentioned technologies, it guides our clients in exploring new realms of opportunity, pushing their campaigns beyond traditional KPIs and towards a holistic strategy.', ['brand' => '<strong>'.$brand.'</strong>']) ?>
                        </div>
                        <div>
                            <?= Yii::t('app', 'In the dizzying digital ecosystem, {brand} is more than a tool: it\'s your essential strategic ally. Our mix of cutting-edge technology, outstanding creativity, and in-depth analysis ensures our partners maintain a leading stance, not only in the current advertising stage but also in the future.', ['brand' => '<strong>'.$brand.'</strong>']) ?>
                        </div>
                    </div>
                </div>

                <div id="sidebar" class="col-md-4">
                    <div class="d-grid sidebar-column-gap">
                        <div>
                            <div class="mb-4">
                                <div class="small text-muted mb-1"><?= date('j F, Y G:i')." GMT" ?></div>
                                <div class="small"><?= Yii::t('app', 'Author: {name}', ['name' => $authorName]) ?></div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="d-grid gap-2">
                                    <?= Icon::widget(['icon' => 'bi-star', 'size' => Icon::SIZE_24]) ?>
                                    <div class="small">120</div>
                                </div>
                                <div>
                                    <?= Icon::widget(['icon' => 'bi-bookmark', 'size' => Icon::SIZE_24]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 line-share pt-4 d-flex gap-3">
                            <?= Icon::widget(['icon' => 'bi-twitter-x', 'size' => Icon::SIZE_24]) ?>
                            <?= Icon::widget(['icon' => 'bi-linkedin', 'size' => Icon::SIZE_24]) ?>
                            <?= Icon::widget(['icon' => 'bi-facebook', 'size' => Icon::SIZE_24]) ?>
                            <?= Icon::widget(['icon' => 'bi-envelope', 'size' => Icon::SIZE_24]) ?>
                            <?= Icon::widget(['icon' => 'bi-bookmark', 'size' => Icon::SIZE_24]) ?>
                        </div>
                        <div>
                            <div class="mb-2"><?= Yii::t('app', 'Recommended topics') ?></div>
                            <div>
                                <?php
                                $labelsBadges = [
                                    "Contextual", "Performance", "Trends", "Advertisers", "AI", "Audience", "RTB", "Platform"
                                ];

                                foreach ($labelsBadges as $label) {
                                    echo Html::tag('span', Yii::t('app', $label), ['class' => 'badge bg-secondary fw-light bg-opacity-50 rounded-pill me-1 mb-2']);
                                }
                                ?>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2"><?= Yii::t('app', 'Recent entries') ?></div>
                            <div class="d-grid gap-3">
                                <div class="d-flex gap-2">
                                    <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/sidebar-1.svg"), ["alt" => "img-sidebar-1", "class" => "align-self-baseline"]) ?>
                                    <div>
                                        <div class="mb-1"><?= Yii::t('app', 'How advanced will our technological way of life be in 2050?') ?></div>
                                        <div class="text-muted small"><?= Yii::t('app', '{min} min read', ['min' => 4]) ?></div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/sidebar-2.svg"), ["alt" => "img-sidebar-2", "class" => "align-self-baseline"]) ?>
                                    <div>
                                        <div class="mb-1"><?= Yii::t('app', 'Shifting trends in how we consume content.') ?></div>
                                        <div class="text-muted small"><?= Yii::t('app', '{min} min read', ['min' => 5]) ?></div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/sidebar-3.svg"), ["alt" => "img-sidebar-3", "class" => "align-self-baseline"]) ?>
                                    <div>
                                        <div class="mb-1"><?= Yii::t('app', 'What are the ethical limitations of AI in the workplace?') ?></div>
                                        <div class="text-muted small"><?= Yii::t('app', '{min} min read', ['min' => 7]) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div>
            <div class="pb-md-5 mb-md-2 pb-4 offset-md-1">
                <div class="d-flex align-items-center mb-4">
                    <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/user.svg"), ["alt" => "user"]) ?>
                    <h5 class="ms-2"><?= Yii::t('app', 'Others articles by {name}', ['name' => $authorName]) ?></h5>
                </div>
                <div class="d-md-flex d-grid gap-4">
                    <div class="col-md-3">
                        <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/footer-1.svg"), ["alt" => "img-footer-1", "class" => "w-100 mb-2"]) ?>
                        <div class="mb-1"><?= Yii::t('app', 'What are the benefits of using generative artificial intelligence?') ?></div>
                        <div class="text-muted small"><?= Yii::t('app', '{min} min read', ['min' => 5]) ?></div>
                    </div>
                    <div class="col-md-3">
                        <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/footer-2.svg"), ["alt" => "img-footer-2", "class" => "w-100 mb-2"]) ?>
                        <div class="mb-1"><?= Yii::t('app', 'ClearLine, OpenPath, and The Premium Marketplace') ?></div>
                        <div class="text-muted small"><?= Yii::t('app', '{min} min read', ['min' => 10]) ?></div>
                    </div>
                    <div class="col-md-3">
                        <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/footer-3.svg"), ["alt" => "img-footer-3", "class" => "w-100 mb-2"]) ?>
                        <div class="mb-1"><?= Yii::t('app', 'The benefits of universal identifiers on connected TV and mobile') ?></div>
                        <div class="text-muted small"><?= Yii::t('app', '{min} min read', ['min' => 8]) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">
            <div class="d-md-flex d-grid gap-md-0 gap-3 offset-md-1">
                <?= yii\helpers\Html::img(Yii::getAlias("@web/images/site-mockup/logo.svg"), ["alt" => "logo-footer", "class" => "logo-footer"]) ?>
                <div class="d-flex gap-5 text-muted small">
                    <div class="d-grid gap-1">
                        <div><?= Yii::t('app', 'Home') ?></div>
                        <div><?= Yii::t('app', 'News') ?></div>
                        <div><?= Yii::t('app', 'Market') ?></div>
                    </div>
                    <div class="d-grid gap-1">
                        <div><?= Yii::t('app', 'Economy') ?></div>
                        <div><?= Yii::t('app', 'Opinion') ?></div>
                        <div><?= Yii::t('app', 'Finance') ?></div>
                    </div>
                    <div class="d-grid gap-1">
                        <div><?= Yii::t('app', 'Companies') ?></div>
                        <div><?= Yii::t('app', 'Environment') ?></div>
                        <div><?= Yii::t('app', 'Podcats') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>