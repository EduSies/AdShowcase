<?php

declare(strict_types=1);

namespace app\services\back_office\sales_type;

use app\models\forms\back_office\SalesTypeForm;
use app\models\SalesType;
use Yii;

final class BackOfficeSalesTypeCreateService
{
    /** Create entity from form (SCENARIO_CREATE). Returns model or null on error. */
    public function create(SalesTypeForm $form): ?SalesType
    {
        $salesType = new SalesType();

        $salesType->setAttributes([
            'hash' => $form->hash ?: Yii::$app->security->generateRandomString(16),
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