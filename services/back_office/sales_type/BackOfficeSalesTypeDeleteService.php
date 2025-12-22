<?php

declare(strict_types=1);

namespace app\services\back_office\sales_type;

use app\models\SalesType;

final class BackOfficeSalesTypeDeleteService
{
    /** Hard delete by PK. Returns true on success. */
    public function delete(string $hash): bool
    {
        $salesType = SalesType::findOne(['hash' => $hash]);

        if (!$salesType) {
            return false;
        }

        if ($salesType->delete() === false) {
            return false;
        }

        return true;
    }
}