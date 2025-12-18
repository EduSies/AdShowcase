<?php

declare(strict_types=1);

namespace app\services\back_office\device;

use app\helpers\StatusHelper;
use app\models\Device;
use yii\helpers\ArrayHelper;

final class DeviceListService
{
    /**
     * Devuelve array [id => name] de dispositivos activos.
     */
    public function getDevicesDropDown(): array
    {
        $rows = Device::find()
            ->select(['id', 'name'])
            ->where(['status' => StatusHelper::STATUS_ACTIVE])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
    }
}