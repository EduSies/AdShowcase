<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\helpers\StatusHelper;
use app\models\Brand;
use yii\helpers\ArrayHelper;

final class BrandListService
{
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