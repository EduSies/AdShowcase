<?php

declare(strict_types=1);

namespace app\services\back_office\agencies;

use app\models\Agency;
use app\models\forms\back_office\AgencyForm;
use Yii;

final class BackOfficeAgencyCreateService
{
    /** Create entity from form (SCENARIO_CREATE). Returns model or null on error. */
    public function create(AgencyForm $form): ?Agency
    {
        $agency = new Agency();

        $agency->setAttributes([
            'hash' => $form->hash ?: Yii::$app->security->generateRandomString(16),
            'name' => $form->name,
            'status' => $form->status,
            'country_id' => $form->country_id,
        ]);

        if (!$agency->save()) {
            $form->addErrors($agency->getErrors());
            return null;
        }

        return $agency;
    }
}