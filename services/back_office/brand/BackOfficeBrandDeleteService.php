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
    public function delete(string $hash): bool
    {
        $brand = Brand::findOne(['hash' => $hash]);

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