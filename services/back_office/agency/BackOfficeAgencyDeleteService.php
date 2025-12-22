<?php

declare(strict_types=1);

namespace app\services\back_office\agency;

use app\models\Agency;

final class BackOfficeAgencyDeleteService
{
    public function delete(string $hash): bool
    {
        $agency = Agency::findOne(['hash' => $hash]);

        if (!$agency) {
            return false;
        }

        if ($agency->delete() === false) {
            return false;
        }

        return true;
    }
}