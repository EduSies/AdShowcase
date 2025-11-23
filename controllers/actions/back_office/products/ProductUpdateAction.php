<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\products;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\ProductForm;
use app\models\Product;
use app\services\back_office\product\BackOfficeProductUpdateService;
use Yii;
use yii\web\NotFoundHttpException;

final class ProductUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = ProductForm::class;
    public ?string $view = '@app/views/back_office/products/' . ProductForm::FORM_NAME;
    public string $idParam = 'id';

    public function run()
    {
        $this->ensureCan($this->can);

        $id = (string)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing id.'));
        }

        $class = $this->modelClass;

        $product = Product::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => ProductForm::SCENARIO_UPDATE]);
//        $model->setAttributes([
//            'id'       => (int)$product->id,
//            'hash'     => $product->hash,
//            'name'     => $product->name,
//            'url_name' => $product->url_name,
//            'status'   => $product->status,
//        ], false);

        if ($model->load(Yii::$app->request->post())) {
            $service = new BackOfficeProductUpdateService();

            if ($service->update($product->id, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect(['back-office/products']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update product.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}