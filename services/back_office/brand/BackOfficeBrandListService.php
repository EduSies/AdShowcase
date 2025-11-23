<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\models\Brand;

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
                'created_at',
                'updated_at'
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();

        return $rows;
    }
}