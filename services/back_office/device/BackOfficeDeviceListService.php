<?php

declare(strict_types=1);

namespace app\services\back_office\device;

use app\helpers\StatusHelper;
use app\models\Device;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

final class BackOfficeDeviceListService
{
    /** Returns flat array for DataTables. */
    public function findAll(): array
    {
        return Device::find()
            ->alias('d')
            ->select([
                'd.*',
                'created_at' => new Expression("DATE_FORMAT(d.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(d.updated_at, '%Y-%m-%d')"),
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
    }

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