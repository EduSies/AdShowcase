<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\brand;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\BrandForm;
use app\services\back_office\brand\BackOfficeBrandCreateService;
use Yii;
use yii\bootstrap5\ActiveForm;

final class BrandCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = BrandForm::class;
    public ?string $view = '@app/views/back_office/brand/' . BrandForm::FORM_NAME;
    public ?array $indexRoute = ['/back-office/brands'];

    public function run()
    {
        $this->ensureCan($this->can);

        $class = $this->modelClass;
        $model = new $class(['scenario' => BrandForm::SCENARIO_CREATE]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeBrandCreateService();
            $ok = $service->create($model);

            if ($ok) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Created successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to create brand.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'status' => \app\helpers\StatusHelper::statusFilter(3),
        ]);
    }
}