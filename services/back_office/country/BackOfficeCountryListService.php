<?php

declare(strict_types=1);

namespace app\services\back_office\country;

use app\helpers\StatusHelper;
use app\models\Country;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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