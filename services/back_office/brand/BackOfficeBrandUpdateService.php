<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\models\Brand;
use app\models\forms\back_office\BrandForm;

final class BackOfficeBrandUpdateService
{
    /**
     * Actualiza una Brand existente (SCENARIO_UPDATE).
     */
    public function update(string $hash, BrandForm $form): bool
    {
        $brand = Brand::findOne(['hash' => $hash]);

        if (!$brand) {
            $form->addErrors($brand->getErrors());
            return false;
        }

        $brand->setAttributes([
            'name' => $form->name,
            'url_slug' => $form->url_slug,
            'status' => $form->status,
        ]);

        if (!empty($form->hash)) {
            $brand->hash = $form->hash;
        }

        if (!$brand->save()) {
            $form->addErrors($brand->getErrors());
            return false;
        }

        return true;
    }
}