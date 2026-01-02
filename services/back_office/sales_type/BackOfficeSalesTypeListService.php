<?php

declare(strict_types=1);

namespace app\services\back_office\sales_type;

use app\helpers\StatusHelper;
use app\models\SalesType;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

final class BackOfficeSalesTypeListService
{
    /** Returns array for DataTables. */
    public function findAll(): array
    {
        return SalesType::find()
            ->alias('s')
            ->select([
                's.*',
                'created_at' => new Expression("DATE_FORMAT(s.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(s.updated_at, '%Y-%m-%d')"),
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
    }

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