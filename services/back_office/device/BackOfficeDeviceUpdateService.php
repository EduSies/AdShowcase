<?php

declare(strict_types=1);

namespace app\services\back_office\device;

use app\models\Device;
use app\models\forms\back_office\DeviceForm;

final class BackOfficeDeviceUpdateService
{
    /** Update entity by PK. Returns true on success. */
    public function update(int $id, DeviceForm $form): bool
    {
        $device = Device::findOne($id);

        if (!$device) {
            $form->addErrors($device->getErrors());
            return false;
        }

        $device->setAttributes([
            'name' => $form->name,
            'status' => $form->status,
        ]);

        if (!empty($form->hash)) {
            $device->hash = $form->hash;
        }

        if (!$device->save()) {
            $form->addErrors($device->getErrors());
            return false;
        }

        return true;
    }
}