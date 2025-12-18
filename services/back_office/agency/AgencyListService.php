<?php

declare(strict_types=1);

namespace app\services\back_office\agency;

use app\helpers\StatusHelper;
use app\models\Agency;
use yii\helpers\ArrayHelper;

final class AgencyListService
{
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