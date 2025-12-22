<?php

declare(strict_types=1);

namespace app\services\back_office\product;

use app\models\Product;

final class BackOfficeProductDeleteService
{
    /** Hard delete by PK. Returns true on success. */
    public function delete(string $hash): bool
    {
        $product = Product::findOne(['hash' => $hash]);

        if (!$product) {
            return false;
        }

        if ($product->delete() === false) {
            return false;
        }

        return true;
    }
}