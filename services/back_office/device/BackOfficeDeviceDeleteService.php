<?php

declare(strict_types=1);

namespace app\services\back_office\device;

use app\models\Device;

final class BackOfficeDeviceDeleteService
{
    /** Hard delete by PK. Returns true on success. */
    public function delete(int $id): bool
    {
        $device = Device::findOne($id);

        if (!$device) {
            $device->addErrors($device->getErrors());
            return false;
        }

        if ($device->delete() === false) {
            $device->addErrors($device->getErrors());
            return false;
        }

        return true;
    }
}
