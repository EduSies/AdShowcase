<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\sales_type;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\SalesTypeForm;
use app\models\SalesType;
use app\services\back_office\sales_type\BackOfficeSalesTypeUpdateService;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;

final class SalesTypeUpdateAction extends BaseBackOfficeAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $modelClass = SalesTypeForm::class;
    public ?string $view = '@app/views/back_office/sales_type/' . SalesTypeForm::FORM_NAME;
    public ?array $indexRoute = ['back-office/sales-types'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $class = $this->modelClass;
        $salesType = SalesType::findOne(['hash' => $hash]);

        if (!$salesType) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => SalesTypeForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => $salesType->id,
            'hash' => $salesType->hash,
            'name' => $salesType->name,
            'status' => $salesType->status,
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeSalesTypeUpdateService();

            if ($service->update($salesType->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update sales type.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'status' => \app\helpers\StatusHelper::statusFilter(3),
        ]);
    }
}