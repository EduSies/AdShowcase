<?php

declare(strict_types=1);

namespace app\services\back_office\salesType;

use app\models\SalesType;

final class BackOfficeSalesTypeDeleteService
{
    /** Hard delete by PK. Returns true on success. */
    public function delete(int $id): bool
    {
        $salesType = SalesType::findOne($id);

        if (!$salesType) {
            $salesType->addErrors($salesType->getErrors());
            return false;
        }

        if ($salesType->delete() === false) {
            $salesType->addErrors($salesType->getErrors());
            return false;
        }

        return true;
    }
}