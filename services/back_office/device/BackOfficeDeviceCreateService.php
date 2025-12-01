<?php

declare(strict_types=1);

namespace app\services\back_office\device;

use app\models\Device;
use app\models\forms\back_office\DeviceForm;
use Yii;

final class BackOfficeDeviceCreateService
{
    /** Create entity from form (SCENARIO_CREATE). Returns model or null on error. */
    public function create(DeviceForm $form): ?Device
    {
        $device = new Device();

        $device->setAttributes([
            'hash' => $form->hash ?: Yii::$app->security->generateRandomString(16),
            'name' => $form->name,
            'status' => $form->status,
        ]);

        if (!$device->save()) {
            $form->addErrors($device->getErrors());
            return null;
        }

        return $device;
    }
}