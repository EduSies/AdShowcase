<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\shared_link;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\SharedLink;
use app\services\back_office\shared_link\BackOfficeSharedLinkDeleteService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class SharedLinkDeleteAction extends BaseBackOfficeAction
{
    public ?string $can = 'share.manage';
    public ?string $modelClass = SharedLink::class;
    public string $idParam = 'hash';
    public ?array $indexRoute = ['/back-office/shared-link'];

    public function run()
    {
        $this->ensureCan($this->can);

        // Obtenemos el hash por GET o POST
        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));

        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $service = new BackOfficeSharedLinkDeleteService();
        $ok = $service->delete($hash);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'success' => $ok,
                'message' => $ok
                    ? Yii::t('app', 'Deleted successfully.')
                    : Yii::t('app', 'Delete failed.'),
            ];
        }

        Yii::$app->session->setFlash(
            $ok ? 'success' : 'error',
            $ok ? Yii::t('app', 'Deleted successfully.') : Yii::t('app', 'Delete failed.')
        );

        return $this->controller->redirect($this->indexRoute);
    }
}