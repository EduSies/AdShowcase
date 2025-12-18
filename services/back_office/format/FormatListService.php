<?php

declare(strict_types=1);

namespace app\services\back_office\format;

use app\helpers\StatusHelper;
use app\models\Format;
use yii\helpers\ArrayHelper;

final class FormatListService
{
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