<?php

use app\helpers\SharedLinkHelper;
use app\widgets\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsVar('ajaxUrlGenerateSharedLink', Url::to(['shared-link/generate']));
$this->registerJsVar('ajaxUrlSendShareEmail', Url::to(['shared-link/send-email']));
$this->registerJsVar('textSendShareEmailValidate', Yii::t('app','Please enter a valid email address'));
$this->registerJsVar('textSendShareEmailError', Yii::t('app', 'Error sending email'));

?>

<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <?= Html::hiddenInput(null, Yii::t('app', 'Hey, AdShowcase has shared a preview with you'), ['id' => 't-share-message']) ?>
            <?= Html::hiddenInput(null, Yii::t('app', 'AdShowcase Preview'), ['id' => 't-share-subject']) ?>

            <?= Html::hiddenInput(null, null, ['id' => 'hidden-creative-title']) ?>
            <?= Html::hiddenInput(null, null, ['id' => 'hidden-creative-format']) ?>
            <?= Html::hiddenInput(null, null, ['id' => 'hidden-creative-agency']) ?>
            <?= Html::hiddenInput(null, null, ['id' => 'hidden-shared-creative-hash']) ?>

            <div class="modal-header border-0 p-5 pt-4 pb-0">
                <h4 class="modal-title text-muted" id="shareModalLabel"><?= Yii::t('app', 'Share Creative') ?></h4>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-5">

                <div id="shareConfigStep">
                    <p class="text-muted mb-4" style="font-size: 0.95rem;">
                        <?= Yii::t('app', 'Configure the link settings before sharing.') ?>
                    </p>

                    <form id="formShareConfig">
                        <div class="row g-4 mb-4">

                            <div class="col-6">
                                <?= Html::label(Yii::t('app', 'Expires in'), 'shareTtl', [
                                    'class' => 'form-label small text-uppercase text-muted mb-2'
                                ]) ?>

                                <?= Html::dropDownList('ttl', '24h', SharedLinkHelper::getTtlOptions(), [
                                    'id' => 'shareTtl',
                                    'class' => 'form-select py-2 bg-light border-0',
                                ]) ?>
                            </div>

                            <div class="col-6">
                                <?= Html::label(Yii::t('app', 'Max Views'), 'shareMaxViews', [
                                    'class' => 'form-label small text-uppercase text-muted mb-2'
                                ]) ?>

                                <?= Html::input('number', 'max_uses', '10', [
                                        'id' => 'shareMaxViews',
                                        'class' => 'form-control py-2 bg-light border-0',
                                        'min' => '1',
                                        'placeholder' => Yii::t('app', 'Leave empty for unlimited')
                                ]) ?>
                            </div>
                        </div>

                        <div class="d-grid pt-4">
                            <?= Html::button(Yii::t('app', 'Generate Link'), [
                                'class' => 'btn btn-primary rounded-pill py-2 shadow-sm',
                                'id' => 'btnGenerateLink',
                                'type' => 'button'
                            ]) ?>
                        </div>
                    </form>
                </div>

                <div id="shareResultStep" class="d-none text-center">

                    <div class="mb-5 d-flex flex-column align-items-center mt-2">
                        <a href="#" id="btnDownloadComposite" class="d-block bg-white p-3 shadow-sm border rounded-3 text-decoration-none d-flex align-items-center justify-content-center hover-scale"
                           style="width: 180px; height: 180px; transition: transform 0.2s;"
                           title="<?= Yii::t('app', 'Download Image') ?>"
                        >
                            <img id="shareQrImage" src="" alt="QR Code" class="img-fluid d-none" crossorigin="anonymous">
                            <div id="qrSpinner" class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden"><?= Yii::t('app', 'Loading...') ?></span>
                            </div>
                        </a>
                        <small class="text-muted mt-2" style="font-size: 0.75rem;">
                            <?= Icon::widget(['icon' => 'bi-download', 'options' => ['class' => 'me-1']]) ?>
                            <?= Yii::t('app', 'Click QR to download') ?>
                        </small>
                    </div>

                    <div class="input-group input-group-lg mb-4 shadow-sm">
                        <?= Html::textInput(null, null, [
                                'id' => 'shareInputUrl',
                                'class' => 'form-control bg-light border-end-0 fs-6 text-muted',
                                'readonly' => true,
                        ]) ?>
                        <?= Html::button(
                                Icon::widget(['icon' => 'bi-clipboard', 'size' => Icon::SIZE_24]),
                                ['id' => 'btnCopyLink', 'class' => 'btn btn-light border border-start-0 text-primary px-4', 'type' => 'button']
                        ) ?>
                    </div>

                    <div id="copySuccessMessage" class="text-success small mb-4 d-none">
                        <?= Icon::widget(['icon' => 'bi-check-circle-fill', 'options' => ['class' => 'me-1']]) ?>
                        <?= Yii::t('app', 'Link copied!') ?>
                    </div>

                    <hr class="my-3 text-muted" style="opacity: 0.1">

                    <div class="text-start mb-2">
                        <label class="form-label small text-uppercase text-muted"><?= Yii::t('app', 'Send via Email') ?></label>
                    </div>
                    <div class="input-group mb-3">
                        <?= Html::input('email', null, null, [
                                'id' => 'shareInputEmailDest',
                                'class' => 'form-control',
                                'placeholder' => 'client@example.com'
                        ]) ?>
                        <button class="btn btn-primary px-4" type="button" id="btnSendEmailTrigger">
                            <?= Icon::widget(['icon' => 'bi-send', 'options' => ['class' => 'me-2']]) ?>
                            <?= Yii::t('app', 'Send') ?>
                        </button>
                    </div>

                    <div class="d-flex justify-content-center mb-4">
                        <a href="#" target="_blank" id="btnShareWhatsapp" class="btn btn-success btn-sm rounded-pill px-3 py-1 d-flex align-items-center gap-2 shadow-sm">
                            <?= Icon::widget(['icon' => 'bi-whatsapp', 'size' => Icon::SIZE_16]) ?>
                            <span>WhatsApp</span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-link text-muted text-decoration-none btn-sm" id="btnResetShare">
                        <?= Icon::widget(['icon' => 'bi-arrow-counterclockwise', 'options' => ['class' => 'me-1']]) ?>
                        <?= Yii::t('app', 'Generate new link') ?>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>