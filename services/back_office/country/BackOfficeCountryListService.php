<?php

declare(strict_types=1);

namespace app\services\back_office\country;

use app\models\Country;
use yii\db\Expression;

final class BackOfficeCountryListService
{
    /** Returns flat array for DataTables. */
    public function findAll(): array
    {
        return Country::find()
            ->alias('c')
            ->select([
                'c.*',
                'created_at' => new Expression("DATE_FORMAT(c.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(c.updated_at, '%Y-%m-%d')"),
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
    }
}