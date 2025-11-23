<?php

declare(strict_types=1);

namespace app\services\back_office\product;

use app\models\Product;
use app\models\forms\back_office\ProductForm;

final class BackOfficeProductUpdateService
{
    /** Update entity by PK. Returns true on success. */
    public function update(int $id, ProductForm $form): bool
    {
        $product = Product::findOne($id);

        if (!$product) {
            $form->addErrors($product->getErrors());
            return false;
        }

        $product->setAttributes([
            'name' => $form->name,
            'url_slug' => $form->url_slug,
            'status' => $form->status,
        ]);

        if (!empty($form->hash)) {
            $product->hash = $form->hash;
        }

        if (!$product->save()) {
            $form->addErrors($product->getErrors());
            return false;
        }

        return true;
    }
}