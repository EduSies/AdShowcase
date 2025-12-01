<?php

declare(strict_types=1);

namespace app\services\back_office\product;

use app\models\Product;
use app\models\forms\back_office\ProductForm;
use Yii;

final class BackOfficeProductCreateService
{
    /** Create entity from form (SCENARIO_CREATE). Returns model or null on error. */
    public function create(ProductForm $form): ?Product
    {
        $product = new Product();

        $product->setAttributes([
            'hash' => $form->hash ?: Yii::$app->security->generateRandomString(16),
            'name' => $form->name,
            'url_slug' => $form->url_slug,
            'status' => $form->status,
        ]);

        if (!$product->save()) {
            $form->addErrors($product->getErrors());
            return null;
        }

        return $product;
    }
}