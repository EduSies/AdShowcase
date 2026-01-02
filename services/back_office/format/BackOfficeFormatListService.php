<?php

declare(strict_types=1);

namespace app\services\back_office\format;

use app\helpers\StatusHelper;
use app\models\Format;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

final class BackOfficeFormatListService
{
    /** Returns array for DataTables. */
    public function findAll(): array
    {
        return Format::find()
            ->alias('f')
            ->select([
                'f.*',
                'created_at' => new Expression("DATE_FORMAT(f.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(f.updated_at, '%Y-%m-%d')"),
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * Devuelve array [id => name] de formatos activos.
     */
    public function getFormatsDropDown(): array
    {
        $rows = Format::find()
            ->select(['id', 'name'])
            ->where(['status' => StatusHelper::STATUS_ACTIVE])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
    }
}