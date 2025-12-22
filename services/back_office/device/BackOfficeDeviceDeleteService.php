<?php

declare(strict_types=1);

namespace app\services\back_office\device;

use app\models\Device;

final class BackOfficeDeviceDeleteService
{
    /** Hard delete by PK. Returns true on success. */
    public function delete(string $hash): bool
    {
        $device = Device::findOne(['hash' => $hash]);

        if (!$device) {
            return false;
        }

        if ($device->delete() === false) {
            return false;
        }

        return true;
    }
}