<?php

declare(strict_types=1);

namespace app\services\back_office\brands;

use app\models\Brand;
use app\models\forms\back_office\BrandForm;
use Yii;

final class BackOfficeBrandCreateService
{
    /**
     * Crea una Brand a partir del BrandForm (SCENARIO_CREATE).
     * Devuelve la Brand persistida o null. $error tendrá el motivo si falla.
     */
    public function create(BrandForm $form): ?Brand
    {
        $brand = new Brand();

        $brand->setAttributes([
            'hash' => $form->hash ?: Yii::$app->security->generateRandomString(16),
            'name' => $form->name,
            'url_name' => $form->url_name,
            'status' => $form->status,
        ]);

        if (!$brand->save()) {
            $form->addErrors($brand->getErrors());
            return null;
        }

        return $brand;
    }
}