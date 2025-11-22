<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\formats;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class FormatDeleteAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = \app\models\Format::class;
    public string $idParam = 'id';

    public function run()
    {
        $this->ensureCan($this->can);
        $id = (string)Yii::$app->request->post($this->idParam, Yii::$app->request->get($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException('Missing id.');
        }

        $class = $this->modelClass;
        /** @var \yii\db\ActiveRecord|null $model */
        $model = $class::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Not found.');
        }

        $ok = (bool)$model->delete();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => $ok];
        }

        Yii::$app->session->setFlash($ok ? 'success' : 'error', $ok ? Yii::t('app', 'Deleted successfully.') : Yii::t('app', 'Delete failed.'));
        return $this->controller->redirect(['backoffice/' . $this->controller->action->id]); // ajusta destino
    }
}