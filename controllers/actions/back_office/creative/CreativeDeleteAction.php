<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\creative;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Creative;
use app\services\back_office\creative\BackOfficeCreativeDeleteService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class CreativeDeleteAction extends BaseBackOfficeAction
{
    public ?string $can = 'creative.manage';
    public ?string $modelClass = Creative::class;
    public string $idParam = 'hash';
    public ?array $indexRoute = ['/back-office/creatives'];

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $service = new BackOfficeCreativeDeleteService();
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