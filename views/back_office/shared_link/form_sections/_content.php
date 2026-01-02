<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\SharedLinkForm $model */
/** @var bool $isUpdate */
/** @var Yii\bootstrap5\ActiveForm $form */
/** @var string $sharedUrl */
/** @var string $privateUrl */
/** @var bool $isRevoked */
/** @var array $accessLogs */
/** @var string $creativeTitle */
/** @var int $usedCount */
/** @var int $sharedLinkHash */
/** @var string|null $revokedAt */

use app\widgets\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

$submitIcon  = $isUpdate ? 'bi-pencil-square' : 'bi-plus-circle';
$submitLabel = $isUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Create');

?>

<div class="row g-3 align-items-end mb-5">
    <div class="col-md-10">
        <label class="form-label text-muted small fw-semibold text-uppercase"><?= Yii::t('app', 'Public Share URL') ?></label>
        <div class="input-group">
            <?= Html::textInput('shared_link_url', $sharedUrl, [
                'class' => 'form-control bg-white font-monospace color-main-1',
                'readonly' => true,
                'id' => 'copy-target-public'
            ]) ?>

            <button class="btn btn-outline-secondary js-copy-btn" type="button" data-target="#copy-target-public">
                <?= Icon::widget(['icon' => 'bi-clipboard']) ?>
                <?= Yii::t('app', 'Copy') ?>
            </button>

            <a href="<?= $sharedUrl ?>" target="_blank" class="btn btn-outline-primary">
                <?= Icon::widget(['icon' => 'bi-box-arrow-up-right']) ?>
                <?= Yii::t('app', 'Open') ?>
            </a>
        </div>
    </div>

    <div class="col-md-10 mt-3">
        <label class="form-label text-muted small fw-semibold text-uppercase d-flex align-items-center">
            <?= Yii::t('app', 'Private Preview URL') ?>
            <span class="badge rounded-pill text-bg-warning border ms-2 shadow-sm"><?= Yii::t('app', 'Login Required') ?></span>
        </label>
        <div class="input-group">
            <?= Html::textInput('private_link_url', $privateUrl, [
                'class' => 'form-control bg-white text-muted font-monospace',
                'readonly' => true,
                'id' => 'copy-target-private'
            ]) ?>

            <button class="btn btn-outline-secondary js-copy-btn" type="button" data-target="#copy-target-private">
                <?= Icon::widget(['icon' => 'bi-clipboard']) ?>
                <?= Yii::t('app', 'Copy') ?>
            </button>

            <a href="<?= $privateUrl ?>" target="_blank" class="btn btn-outline-primary">
                <?= Icon::widget(['icon' => 'bi-box-arrow-up-right']) ?>
                <?= Yii::t('app', 'Open') ?>
            </a>
        </div>
        <div class="form-text small text-warning">
            <?= Icon::widget(['icon' => 'bi-exclamation-triangle-fill', 'options' => ['class' => 'me-2']]) ?>
            <?= Yii::t('app', 'This link requires an active session. Do not share with external clients.') ?>
        </div>
    </div>

    <?php if ($isRevoked): ?>
        <div class="col-10 mt-5">
            <div class="alert alert-danger border-danger d-flex align-items-center text-danger shadow-sm" role="alert">
                <?= Icon::widget(['icon' => 'bi-x-circle-fill', 'size' => Icon::SIZE_24, 'options' => ['class' => 'me-2']]) ?>
                <span><?= Yii::t('app', 'Access Revoked on {date}', ['date' => Yii::$app->formatter->asDatetime($revokedAt)]) ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row g-3">
    <div class="col-md-6 mb-3">
        <label class="form-label"><?= Yii::t('app', 'Creative name') ?></label>
        <?= Html::textInput('creative_name', $creativeTitle, [
            'class' => 'form-control bg-light',
            'disabled' => true,
        ]) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <?php
        $expiresValue = ($model->expires_at) ? date('Y-m-d\TH:i', strtotime($model->expires_at)) : '';
        ?>
        <?= $form->field($model, 'expires_at', [
                'template' => "{label}\n{input}\n{hint}\n{error}"
        ])->textInput([
            'type' => 'datetime-local',
            'value' => $expiresValue
        ])->hint(Yii::t('app', 'Leave empty for unlimited')) ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'max_uses', [
            'template' => "{label}\n{input}\n{hint}\n{error}"
        ])->textInput([
            'placeholder' => Yii::t('app', 'Leave empty for unlimited')
        ])->hint(Yii::t('app', 'Used: {count}', ['count' => $usedCount])) ?>
    </div>

    <div class="col-10">
        <?= $form->field($model, 'note')->textarea([
            'rows' => 3,
            'placeholder' => Yii::t('app', 'Add an internal note about this link...')
        ]) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-10 border-top mt-4">
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <?php if (!$isRevoked): ?>
                    <?= Html::a(
                        Icon::widget(['icon' => 'bi-slash-circle']) . ' ' . Yii::t('app', 'Revoke Access'),
                        'javascript:void(0);',
                        ['class' => 'btn btn-outline-danger rounded-pill js-revoke',]
                    ) ?>
                <?php endif; ?>
            </div>

            <div>
                <?= Html::submitButton(
                    Icon::widget([
                        'icon' => $submitIcon,
                        'size' => Icon::SIZE_24,
                        'options' => ['class' => 'flex-shrink-0'],
                    ]) .
                    Html::tag('span', $submitLabel, ['class' => 'ms-2']),
                    [
                        'class'  => ($isUpdate ? 'btn btn-primary' : 'btn btn-success') . ' rounded-pill px-4',
                        'encode' => false,
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-10 mt-4">

        <div class="logs-section mt-5">
            <h5 class="my-3 text-secondary py-2">
                <?= Icon::widget(['icon' => 'bi-clock-history']) ?>
                <?= Yii::t('app', 'Access History') ?>
            </h5>

            <?php if (!empty($accessLogs)): ?>
                <div class="table-responsive shadow-sm rounded border">
                    <table class="table table-hover table-striped mb-0 align-middle small">
                        <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center" style="width: 50px">#</th>
                            <th scope="col"><?= Yii::t('app', 'Date') ?></th>
                            <th scope="col"><?= Yii::t('app', 'IP') ?></th>
                            <th scope="col"><?= Yii::t('app', 'Device / Browser') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($accessLogs as $index => $log): ?>
                            <tr>
                                <td class="text-muted text-center"><?= $index + 1 ?></td>
                                <td class="fw-semibold text-nowrap">
                                    <?= Yii::$app->formatter->asDatetime($log->accessed_at) ?>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-secondary font-monospace d-flex justify-content-center align-items-center">
                                        <?= Html::encode($log->ip) ?>
                                    </span>
                                </td>
                                <td class="text-muted text-break">
                                    <?= Html::encode($log->user_agent) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-danger border-danger d-flex align-items-center text-danger shadow-sm" role="alert">
                    <?= Icon::widget(['icon' => 'bi-info-circle', 'options' => ['class' => 'me-2']]) ?>
                    <div><?= Yii::t('app', 'No access logs recorded yet.') ?></div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php

$copiedHtml = Icon::widget(['icon' => 'bi-check-lg']) . ' ' . Yii::t('app', 'Copied!');

$ajaxUrlRevoke = Url::to(['shared-link-revoke', 'hash' => $sharedLinkHash]);
$confirmMsg = Yii::t('app', 'Are you sure? This action is irreversible.');
$continueText = Yii::t('app', 'Continue');
$cancelText = Yii::t('app', 'Cancel');

$js = <<<JS
    $('.js-copy-btn').on('click', function() {
        var btn = $(this);
        var targetSelector = btn.data('target');
        var input = $(targetSelector)[0];
        
        if (!input) return;

        var textToCopy = input.value;

        // Función para mostrar feedback visual
        var showSuccessFeedback = function() {
            if (!btn.data('original-html')) {
                btn.data('original-html', btn.html());
            }
            // Cambiamos estilo a verde
            btn.removeClass('btn-outline-secondary').addClass('btn-success');
            btn.html('$copiedHtml');

            // Revertimos después de 2 segundos
            setTimeout(function() {
                btn.html(btn.data('original-html'));
                btn.removeClass('btn-success').addClass('btn-outline-secondary');
            }, 2000);
        };
        
        // Intentamos copiar usando API moderna o fallback
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy)
                .then(showSuccessFeedback)
                .catch(function(err) { console.error('Async: Could not copy text: ', err); });
        } else {
            // Estrategia Fallback
            var textArea = document.createElement("textarea");
            textArea.value = textToCopy;
            textArea.style.position = "fixed"; textArea.style.left = "-9999px"; textArea.style.top = "0";
            document.body.appendChild(textArea);
            textArea.focus(); textArea.select();
            try {
                if (document.execCommand('copy')) showSuccessFeedback();
            } catch (err) { console.error('Fallback error', err); }
            document.body.removeChild(textArea);
        }
    });

    // Lógica de Revocar (se mantiene igual)
    $(document).on('click', '.js-revoke', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        swalFire({
            title: "$confirmMsg",
            confirmButtonText: "$continueText",
            cancelButtonText: "$cancelText",
            customClass: {container: 'swal2-cancel-pr-container'}
        }).then((dialog) => {
            if (dialog.isConfirmed) {
                $.ajax({
                    method: 'post',
                    url: "$ajaxUrlRevoke",
                }).done(function (response) {
                    if (response.success === true) {
                        swalSuccess(response.message);
                        setTimeout(function() { window.location.reload(); }, 3000);
                    } else {
                        swalDanger(response.message);
                    }
                }).fail(function() { swalDanger('Error processing request.'); });
            }
        })
    });
JS;

$this->registerJs($js, \yii\web\View::POS_READY);

?>