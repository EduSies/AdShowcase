<?php

declare(strict_types=1);

namespace app\services\back_office\product;

use app\helpers\StatusHelper;
use app\models\Product;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

final class BackOfficeProductListService
{
    /** Returns flat array for DataTables. */
    public function findAll(): array
    {
        return Product::find()
            ->alias('p')
            ->select([
                'p.*',
                'created_at' => new Expression("DATE_FORMAT(p.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(p.updated_at, '%Y-%m-%d')"),
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * Devuelve array [id => name] de productos activos.
     */
    public function getProductsDropDown(): array
    {
        $rows = Product::find()
            ->select(['id', 'name'])
            ->where(['status' => StatusHelper::STATUS_ACTIVE])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
    }
}