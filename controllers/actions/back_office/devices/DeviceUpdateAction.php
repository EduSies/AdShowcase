<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\devices;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Device;
use app\models\forms\back_office\DeviceForm;
use app\services\back_office\device\BackOfficeDeviceUpdateService;
use Yii;
use yii\web\NotFoundHttpException;

final class DeviceUpdateAction extends BaseBackOfficeAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $modelClass = DeviceForm::class;
    public ?string $view = '@app/views/back_office/devices/' . DeviceForm::FORM_NAME;
    public string $idParam = 'id';

    public function run()
    {
        $this->ensureCan($this->can);

        $id = (string)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing id.'));
        }

        $class = $this->modelClass;

        $device = Device::findOne($id);
        if (!$device) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => DeviceForm::SCENARIO_UPDATE]);
//        $model->setAttributes([
//            'id'       => (int)$device->id,
//            'hash'     => $device->hash,
//            'name'     => $device->name,
//            'url_name' => $device->url_name,
//            'status'   => $device->status,
//        ], false);

        if ($model->load(Yii::$app->request->post())) {
            $service = new BackOfficeDeviceUpdateService();

            if ($service->update($device->id, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect(['back-office/brands']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update device.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}