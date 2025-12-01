<?php

declare(strict_types=1);

namespace app\services\back_office\sales_type;

use app\models\forms\back_office\SalesTypeForm;
use app\models\SalesType;

final class BackOfficeSalesTypeUpdateService
{
    /** Update entity by PK. Returns true on success. */
    public function update(string $hash, SalesTypeForm $form): bool
    {
        $salesType = SalesType::findOne(['hash' => $hash]);

        if (!$salesType) {
            $form->addErrors($salesType->getErrors());
            return false;
        }

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