<?php

declare(strict_types=1);

namespace app\services\back_office\country;

use app\models\Country;

final class BackOfficeCountryDeleteService
{
    public function delete(string $hash): bool
    {
        $country = Country::findOne(['hash' => $hash]);

        if (!$country) {
            return false;
        }

        if ($country->delete() === false) {
            return false;
        }

        return true;
    }
}