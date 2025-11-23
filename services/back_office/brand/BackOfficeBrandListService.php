<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\models\Brand;
use yii\db\Expression;

final class BackOfficeBrandListService
{
    public function findAll(): array
    {
        $rows = Brand::find()
            ->select([
                'id',
                'hash',
                'name',
                'url_name',
                'status',
                new Expression("DATE_FORMAT([[created_at]], '%Y-%m-%d') AS created_at"),
                new Expression("DATE_FORMAT([[updated_at]], '%Y-%m-%d') AS updated_at"),
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();

        return $rows;
    }
}