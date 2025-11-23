<?php

declare(strict_types=1);

namespace app\services\back_office\salesType;

use app\models\SalesType;
use app\models\forms\back_office\SalesTypeForm;

final class BackOfficeSalesTypeCreateService
{
    /** Create entity from form (SCENARIO_CREATE). Returns model or null on error. */
    public function create(SalesTypeForm $form): ?SalesType
    {
        $salesType = new SalesType();

        $salesType->setAttributes([
            'name' => $form->name,
            'status' => $form->status,
        ]);

        if (!$salesType->save()) {
            $form->addErrors($salesType->getErrors());
            return null;
        }

        return $salesType;
    }
}