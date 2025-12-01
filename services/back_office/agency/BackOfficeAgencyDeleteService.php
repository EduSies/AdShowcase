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
            $agency->addErrors($agency->getErrors());
            return false;
        }

        if ($agency->delete() === false) {
            $agency->addErrors($agency->getErrors());
            return false;
        }

        return true;
    }
}