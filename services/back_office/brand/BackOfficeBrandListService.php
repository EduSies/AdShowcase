<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\helpers\StatusHelper;
use app\models\Brand;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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

    /**
     * Devuelve array [id => name] de marcas activas.
     */
    public function getBrandsDropDown(): array
    {
        $rows = Brand::find()
            ->select(['id', 'name'])
            ->where(['status' => StatusHelper::STATUS_ACTIVE])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
    }
}