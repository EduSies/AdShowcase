<?php

declare(strict_types=1);

namespace app\services\back_office\country;

use app\models\Country;
use app\models\forms\back_office\CountryForm;
use Yii;

final class BackOfficeCountryCreateService
{
    /** Create entity from form (SCENARIO_CREATE). Returns model or null on error. */
    public function create(CountryForm $form): ?Country
    {
        $country = new Country();

        $country->setAttributes([
            'hash' => $form->hash ?: Yii::$app->security->generateRandomString(16),
            'iso' => $form->iso,
            'iso3' => $form->iso3,
            'name' => $form->name,
            'continent_code' => $form->continent_code,
            'currency_code' => $form->currency_code,
            'status' => $form->status,
            'url_slug' => $form->url_slug,
        ]);

        if (!$country->save()) {
            $form->addErrors($country->getErrors());
            return null;
        }

        return $country;
    }
}