<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\shared_link;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/** POST: id => revoca (marca revoked_at=NOW()) */
final class SharedLinkRevokeAction extends BaseBackofficeAction
{
    public ?string $can = 'share.manage';
    public ?string $modelClass = \app\models\SharedLink::class;

    public function run(): array
    {
        $this->ensureCan($this->can);
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = (string)Yii::$app->request->post('id', '');
        if ($id === '') {
            throw new NotFoundHttpException('Missing id.');
        }

        $class = $this->modelClass;
        $model = $class::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Not found.');
        }

        $model->revoked_at = date('Y-m-d H:i:s');
        return ['success' => (bool)$model->save(false, ['revoked_at'])];
    }
}