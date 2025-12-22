<?php

declare(strict_types=1);

namespace app\services\back_office\product;

use app\helpers\StatusHelper;
use app\models\Product;
use yii\helpers\ArrayHelper;

final class ProductListService
{
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