<?php

declare(strict_types=1);

namespace app\services\back_office\country;

use app\models\Country;
use app\models\forms\back_office\CountryForm;

final class BackOfficeCountryUpdateService
{
    public function update(string $hash, CountryForm $form): bool
    {
        $country = Country::findOne(['hash' => $hash]);

        if (!$country) {
            return false;
        }

        $country->setAttributes([
            'iso' => $form->iso,
            'iso3' => $form->iso3,
            'name' => $form->name,
            'continent_code' => $form->continent_code,
            'currency_code' => $form->currency_code,
            'status' => $form->status,
            'url_slug' => $form->url_slug,
        ]);

        if (!empty($form->hash)) {
            $country->hash = $form->hash;
        }

        if (!$country->save()) {
            $form->addErrors($country->getErrors());
            return false;
        }

        return true;
    }
}