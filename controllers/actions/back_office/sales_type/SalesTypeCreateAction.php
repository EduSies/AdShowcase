<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\sales_type;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\SalesTypeForm;
use app\services\back_office\sales_type\BackOfficeSalesTypeCreateService;
use Yii;

final class SalesTypeCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = SalesTypeForm::class;
    public ?string $view = '@app/views/back_office/sales_type/' . SalesTypeForm::FORM_NAME;
    public ?array $indexRoute = ['back-office/sales-types'];

    public function run()
    {
        $this->ensureCan($this->can);

        $class = $this->modelClass;
        $model = new $class(['scenario' => SalesTypeForm::SCENARIO_CREATE]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeSalesTypeCreateService();
            $ok = $service->create($model);

            if ($ok) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Created successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to create sales type.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
        ]);
    }
}