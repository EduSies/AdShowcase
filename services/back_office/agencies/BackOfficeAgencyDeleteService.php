<?php

declare(strict_types=1);

namespace app\services\back_office\agencies;

use app\models\Agency;

final class BackOfficeAgencyDeleteService
{
    public function delete(int $id): bool
    {
        $agency = Agency::findOne($id);

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