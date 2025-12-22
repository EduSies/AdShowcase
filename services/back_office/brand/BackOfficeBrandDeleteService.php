<?php

declare(strict_types=1);

namespace app\services\back_office\brand;

use app\models\Brand;

final class BackOfficeBrandDeleteService
{
    /**
     * Elimina una Brand (SCENARIO_DELETE).
     */
    public function delete(string $hash): bool
    {
        $brand = Brand::findOne(['hash' => $hash]);

        if (!$brand) {
            return false;
        }

        if ($brand->delete() === false) {
            return false;
        }

        return true;
    }
}