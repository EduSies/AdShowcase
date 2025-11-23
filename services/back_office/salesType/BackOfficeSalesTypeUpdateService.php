<?php

declare(strict_types=1);

namespace app\services\back_office\salesType;

use app\models\SalesType;
use app\models\forms\back_office\SalesTypeForm;

final class BackOfficeSalesTypeUpdateService
{
    /** Update entity by PK. Returns true on success. */
    public function update(int $id, SalesTypeForm $form): bool
    {
        $salesType = SalesType::findOne($id);

        $salesType->setAttributes([
            'name' => $form->name,
            'status' => $form->status,
        ]);

        if (!empty($form->hash)) {
            $salesType->hash = $form->hash;
        }

        if (!$salesType->save()) {
            $form->addErrors($salesType->getErrors());
            return false;
        }

        return true;
    }
}