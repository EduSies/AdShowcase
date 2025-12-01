<?php

declare(strict_types=1);

namespace app\services\back_office\sales_type;

use app\models\SalesType;
use yii\db\Expression;

final class BackOfficeSalesTypeListService
{
    /** Returns flat array for DataTables. */
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
}