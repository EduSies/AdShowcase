<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\shared_link;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\SharedLink;
use app\services\back_office\shared_link\BackOfficeSharedLinkRevokeService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class SharedLinkRevokeAction extends BaseBackOfficeAction
{
    public ?string $can = 'share.manage';
    public ?string $modelClass = SharedLink::class;
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));

        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $service = new BackOfficeSharedLinkRevokeService();
        $ok = $service->revoke($hash);

        // Respuesta AJAX
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'success' => $ok,
                'message' => $ok
                    ? Yii::t('app', 'Access revoked successfully.')
                    : Yii::t('app', 'Revoke failed.'),
            ];
        }

        // Respuesta Estándar (Flash + Redirección)
        Yii::$app->session->setFlash(
            $ok ? 'success' : 'error',
            $ok ? Yii::t('app', 'Access revoked successfully.') : Yii::t('app', 'Revoke failed.')
        );

        return $this->controller->redirect(Yii::$app->request->referrer);
    }
}