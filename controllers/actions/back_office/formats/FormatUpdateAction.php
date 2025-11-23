<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\formats;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Format;
use app\models\Forms\back_office\FormatForm;
use app\services\back_office\format\BackOfficeFormatUpdateService;
use Yii;
use yii\web\NotFoundHttpException;

final class FormatUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = FormatForm::class;
    public ?string $view = '@app/views/back_office/formats/' . FormatForm::FORM_NAME;
    public string $idParam = 'id';

    public function run()
    {
        $this->ensureCan($this->can);

        $id = (string)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing id.'));
        }

        $class = $this->modelClass;

        $format = Format::findOne($id);
        if (!$format) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => FormatForm::SCENARIO_UPDATE]);
//        $model->setAttributes([
//            'id'       => (int)$format->id,
//            'hash'     => $format->hash,
//            'name'     => $format->name,
//            'url_name' => $format->url_name,
//            'status'   => $format->status,
//        ], false);

        if ($model->load(Yii::$app->request->post())) {
            $service = new BackOfficeFormatUpdateService();

            if ($service->update($format->id, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect(['back-office/formats']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update format.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}