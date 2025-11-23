<?php

declare(strict_types=1);

namespace app\services\back_office\agencies;

use app\models\Agency;
use app\models\forms\back_office\AgencyForm;

final class BackOfficeAgencyUpdateService
{
    public function update(int $id, AgencyForm $form): bool
    {
        $agency = Agency::findOne($id);

        if (!$agency) {
            $form->addErrors($agency->getErrors());
            return false;
        }

        $agency->setAttributes([
            'hash' => $form->hash,
            'name' => $form->name,
            'country_id' => $form->country_id,
            'status' => $form->status,
        ]);

        if (!empty($form->hash)) {
            $agency->hash = $form->hash;
        }

        if (!$agency->save()) {
            $form->addErrors($agency->getErrors());
            return false;
        }

        return true;
    }
}