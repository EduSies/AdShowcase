<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\formats;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Format;
use app\services\back_office\format\BackOfficeFormatDeleteService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class FormatDeleteAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = Format::class;
    public string $idParam = 'id';
    public ?string $view = '@app/views/back_office/formats/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $id = (int)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Missing id.'));
        }

        $service = new BackOfficeFormatDeleteService();
        $ok = $service->delete($id);

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

        return $this->controller->redirect(['back-office/formats']);
    }
}