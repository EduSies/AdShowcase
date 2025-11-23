<?php

declare(strict_types=1);

namespace app\services\back_office\device;

use app\models\Device;
use app\models\forms\back_office\DeviceForm;

final class BackOfficeDeviceCreateService
{
    /** Create entity from form (SCENARIO_CREATE). Returns model or null on error. */
    public function create(DeviceForm $form): ?Device
    {
        $device = new Device();

        $device->setAttributes([
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