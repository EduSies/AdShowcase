<?php

declare(strict_types=1);

namespace app\services\back_office\device;

use app\models\Device;
use yii\db\Expression;

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
}
