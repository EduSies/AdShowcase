<?php

declare(strict_types=1);

namespace app\services\back_office\product;

use app\models\Product;

final class BackOfficeProductDeleteService
{
    /** Hard delete by PK. Returns true on success. */
    public function delete(int $id): bool
    {
        $product = Product::findOne($id);

        if (!$product) {
            $product->addErrors($product->getErrors());
            return false;
        }

        if ($product->delete() === false) {
            $product->addErrors($product->getErrors());
            return false;
        }

        return true;
    }
}