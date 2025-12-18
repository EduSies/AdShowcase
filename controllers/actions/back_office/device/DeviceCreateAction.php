<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\device;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\DeviceForm;
use app\services\back_office\device\BackOfficeDeviceCreateService;
use Yii;
use yii\bootstrap5\ActiveForm;

final class DeviceCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = DeviceForm::class;
    public ?string $view = '@app/views/back_office/device/' . DeviceForm::FORM_NAME;
    public ?array $indexRoute = ['/back-office/devices'];

    public function run()
    {
        $this->ensureCan($this->can);

        $class = $this->modelClass;
        $model = new $class(['scenario' => DeviceForm::SCENARIO_CREATE]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeDeviceCreateService();
            $ok = $service->create($model);

            if ($ok) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Created successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to create device.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'status' => \app\helpers\StatusHelper::statusFilter(3),
        ]);
    }
}