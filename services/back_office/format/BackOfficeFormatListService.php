<?php

declare(strict_types=1);

namespace app\services\back_office\format;

use app\models\Format;
use yii\db\Expression;

final class BackOfficeFormatListService
{
    /** Returns flat array for DataTables. */
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
}