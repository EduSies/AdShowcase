<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\Icon;

$this->title = $name;

?>

<div class="container my-5 site-error">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 mt-5">
            <div class="card shadow-lg border-0 mt-5">
                <div class="card-body text-center p-4 p-md-5">

                    <div class="mb-3 display-4">
                        <span aria-hidden="true">😕</span>
                    </div>

                    <h1 class="h3 mb-3">
                        <?= Html::encode($this->title) ?>
                    </h1>

                    <div class="alert alert-danger text-start small mb-4" role="alert">
                        <?= nl2br(Html::encode($message)) ?>
                    </div>

                    <p class="text-muted small mb-1">
                        <?= Yii::t('app', 'The above error occurred while the server was processing your request.') ?>
                    </p>
                    <p class="text-muted small mb-4">
                        <?= Yii::t('app', 'If you believe this is a server error, please contact the administrator.') ?>
                    </p>

                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 mt-3">
                        <?= Html::button(
                            Icon::widget([
                                'icon' => 'bi-arrow-left',
                                'size' => Icon::SIZE_24,
                                'options' => ['class' => 'flex-shrink-0'],
                            ]) . Html::tag('span', Yii::t('app', 'Go back'), ['class' => 'ms-2']),
                            [
                                'type' => 'button',
                                'class' => 'btn btn-primary d-inline-flex align-items-center justify-content-center',
                                'onclick' => "if (window.history.length > 1) { window.history.back(); } else { window.location.href = '" . Url::to(Yii::$app->homeUrl) . "'; }",
                            ]
                        ) ?>

                        <?= Html::a(
                            Icon::widget([
                                'icon' => 'bi-house',
                                'size' => Icon::SIZE_24,
                                'options' => ['class' => 'flex-shrink-0'],
                            ]) . Html::tag('span', Yii::t('app', 'Go to homepage'), ['class' => 'ms-2']),
                            Url::to(Yii::$app->homeUrl),
                            [
                                'class' => 'btn btn-outline-secondary d-inline-flex align-items-center justify-content-center',
                            ]
                        ) ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>