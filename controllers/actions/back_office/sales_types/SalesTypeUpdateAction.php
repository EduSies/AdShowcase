<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\sales_types;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\SalesTypeForm;
use app\models\SalesType;
use app\services\back_office\salesType\BackOfficeSalesTypeUpdateService;
use Yii;
use yii\web\NotFoundHttpException;

final class SalesTypeUpdateAction extends BaseBackOfficeAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $modelClass = SalesTypeForm::class;
    public ?string $view = '@app/views/back_office/sales_types/' . SalesTypeForm::FORM_NAME;
    public string $idParam = 'id';

    public function run()
    {
        $this->ensureCan($this->can);

        $id = (string)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing id.'));
        }

        $class = $this->modelClass;

        $salesType = SalesType::findOne($id);
        if (!$salesType) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => SalesTypeForm::SCENARIO_UPDATE]);
//        $model->setAttributes([
//            'id'       => (int)$salesType->id,
//            'hash'     => $salesType->hash,
//            'name'     => $salesType->name,
//            'url_name' => $salesType->url_name,
//            'status'   => $salesType->status,
//        ], false);

        if ($model->load(Yii::$app->request->post())) {
            $service = new BackOfficeSalesTypeUpdateService();

            if ($service->update($salesType->id, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect(['back-office/sales-types']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update sales type.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}