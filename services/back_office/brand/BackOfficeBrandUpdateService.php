<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\models\Brand;
use app\models\forms\back_office\BrandForm;
use Yii;

final class BackOfficeBrandUpdateService
{
    /**
     * Actualiza una Brand existente (SCENARIO_UPDATE).
     */
    public function update(int $id, BrandForm $form): bool
    {
        $brand = Brand::findOne($id);
        if (!$brand) {
            $form->addErrors($brand->getErrors());
            return false;
        }

        $brand->setAttributes([
            'name' => $form->name,
            'url_name' => $form->url_name,
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