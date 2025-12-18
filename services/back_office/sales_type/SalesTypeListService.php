<?php

declare(strict_types=1);

namespace app\services\back_office\sales_type;

use app\helpers\StatusHelper;
use app\models\SalesType;
use yii\helpers\ArrayHelper;

final class SalesTypeListService
{
    /**
     * Devuelve array [id => name] de tipos de venta activos.
     */
    public function getSalesTypesDropDown(): array
    {
        $rows = SalesType::find()
            ->select(['id', 'name'])
            ->where(['status' => StatusHelper::STATUS_ACTIVE])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
    }
}