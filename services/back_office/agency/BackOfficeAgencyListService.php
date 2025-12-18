<?php

declare(strict_types=1);

namespace app\services\back_office\agency;

use app\models\Agency;
use yii\db\Expression;

final class BackOfficeAgencyListService
{
    /** Returns flat array for DataTables. */
    public function findAll(): array
    {
        return Agency::find()
            ->alias('a')
            ->select([
                'a.*',
                'country_name' => 'c.name',
                'created_at' => new Expression("DATE_FORMAT(a.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(a.updated_at, '%Y-%m-%d')"),
            ])
            ->joinWith(['country c'], false)
            ->asArray()
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }
}