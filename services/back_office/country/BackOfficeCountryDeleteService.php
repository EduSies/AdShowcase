<?php

declare(strict_types=1);

namespace app\services\back_office\country;

use app\models\Country;

final class BackOfficeCountryDeleteService
{
    public function delete(int $id): bool
    {
        $country = Country::findOne($id);

        if (!$country) {
            $country->addErrors($country->getErrors());
            return false;
        }

        if ($country->delete() === false) {
            $country->addErrors($country->getErrors());
            return false;
        }

        return true;
    }
}