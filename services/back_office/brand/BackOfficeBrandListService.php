<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\models\Brand;
use yii\db\Expression;

final class BackOfficeBrandListService
{
    public function findAll(): array
    {
        return Brand::find()
            ->alias('b')
            ->select([
                'b.*',
                'created_at' => new Expression("DATE_FORMAT(b.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(b.updated_at, '%Y-%m-%d')"),
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
    }
}