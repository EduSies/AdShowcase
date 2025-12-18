<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\device;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Device;
use app\models\forms\back_office\DeviceForm;
use app\services\back_office\device\BackOfficeDeviceUpdateService;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;

final class DeviceUpdateAction extends BaseBackOfficeAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $modelClass = DeviceForm::class;
    public ?string $view = '@app/views/back_office/device/' . DeviceForm::FORM_NAME;
    public ?array $indexRoute = ['back-office/devices'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $class = $this->modelClass;
        $device = Device::findOne(['hash' => $hash]);

        if (!$device) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => DeviceForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => $device->id,
            'hash' => $device->hash,
            'name' => $device->name,
            'status' => $device->status,
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeDeviceUpdateService();

            if ($service->update($device->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update device.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'status' => \app\helpers\StatusHelper::statusFilter(3),
        ]);
    }
}