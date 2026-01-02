<?php

declare(strict_types=1);

namespace app\services\back_office\creative;

use app\models\Creative;
use yii\db\Expression;

final class BackOfficeCreativeListService
{
    /** Returns array for DataTables with joined relations. */
    public function findAll(): array
    {
        return Creative::find()
            ->alias('c')
            ->select([
                'c.*',
                'brand_name' => 'b.name',
                'agency_name' => 'a.name',
                'country_name' => 'co.name',
                'format_name' => 'f.name',
                'sales_type_name' => 'st.name',
                'created_at' => new Expression("DATE_FORMAT(c.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(c.updated_at, '%Y-%m-%d')"),
            ])
            ->joinWith(['brand b', 'agency a', 'country co', 'format f', 'salesType st'], false)
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * Obtiene una creatividad con todas sus relaciones por Hash
     */
    public function getOne(string $hash): ?array
    {
        return Creative::find()
            ->alias('c')
            ->select(['c.*'])
            ->joinWith(['brand b', 'agency a', 'country co', 'format f', 'salesType st', 'device d', 'assetFile af'], true)
            ->where(['c.hash' => $hash])
            ->asArray()
            ->one();
    }
}