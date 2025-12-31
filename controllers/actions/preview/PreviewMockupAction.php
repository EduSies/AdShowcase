<?php

declare(strict_types=1);

namespace app\controllers\actions\preview;

use app\helpers\PreviewHelper;
use app\services\back_office\creative\BackOfficeCreativeListService;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

final class PreviewMockupAction extends BasePreviewAction
{
    public string|bool $layout = false;

    public function run($hash)
    {
        $service = new BackOfficeCreativeListService();
        $creative = $service->getOne($hash);

        if (!$creative) {
            throw new NotFoundHttpException(Yii::t('app', 'Creative not found'));
        }

        $vastXml = null;

        // Click URL o fallback
        $clickUrl = $creative['click_url'] ?? '#';

        if (isset($creative['assetFile']) && !empty($creative['assetFile'])) {
            $asset = $creative['assetFile'];
            $assetUrl = Url::to($asset['storage_path'], true);
            $mime = $asset['mime'];

            // Generamos el XML SIEMPRE (tu Helper ya decide si es Linear o NonLinear)
            $vastXml = PreviewHelper::generateVast(
                (int)$creative['id'],
                $creative['title'],
                $clickUrl,
                $assetUrl,
                $mime,
                (int)($asset['width'] ?? 0),
                (int)($asset['height'] ?? 0),
                (int)($asset['duration_sec'] ?? 15)
            );
        }

        $authorName = 'Raquel Sherman';
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $authorName = trim(($identity->name ?? '') . ' ' . ($identity->surname ?? ''));
        }

        return $this->controller->render('site-mockup', [
            'creative' => $creative,
            'vastXml' => $vastXml,
            'authorName' => $authorName,
        ]);
    }
}