<?php

declare(strict_types=1);

namespace app\services\back_office\country;

use app\helpers\StatusHelper;
use app\models\Country;
use yii\helpers\ArrayHelper;

final class CountryListService
{
    /**
     * Devuelve un array [id => name] de todos los países directamente de la BD.
     */
    public function getCountriesDropDown(): array
    {
        $rows = Country::find()
            ->select(['id', 'name'])
            ->where(['status' => StatusHelper::STATUS_ACTIVE])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
    }
}