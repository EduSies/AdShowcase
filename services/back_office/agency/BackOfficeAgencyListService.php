<?php

declare(strict_types=1);

namespace app\services\back_office\agency;

use app\helpers\StatusHelper;
use app\models\Agency;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

final class BackOfficeAgencyListService
{
    /** Returns array for DataTables. */
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

    /**
     * Devuelve array [id => name] de agencias activas.
     */
    public function getAgenciesDropDown(): array
    {
        $rows = Agency::find()
            ->select(['id', 'name'])
            ->where(['status' => StatusHelper::STATUS_ACTIVE])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
    }
}