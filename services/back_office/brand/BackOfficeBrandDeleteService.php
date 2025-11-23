<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\models\Brand;
use Yii;

final class BackOfficeBrandDeleteService
{
    /**
     * Elimina una Brand (SCENARIO_DELETE).
     */
    public function delete(int $id): bool
    {
        $brand = Brand::findOne($id);
        if (!$brand) {
            $brand->addErrors($brand->getErrors());
            return false;
        }

        if ($brand->delete() === false) {
            $brand->addErrors($brand->getErrors());
            return false;
        }

        return true;
    }
}