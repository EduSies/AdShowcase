<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\product;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\ProductForm;
use app\services\back_office\product\BackOfficeProductCreateService;
use Yii;

final class ProductCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = ProductForm::class;
    public ?string $view = '@app/views/back_office/product/' . ProductForm::FORM_NAME;

    public function run()
    {
        $this->ensureCan($this->can);

        $class = $this->modelClass;
        $model = new $class(['scenario' => ProductForm::SCENARIO_CREATE]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeProductCreateService();
            $ok = $service->create($model);

            if ($ok) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Created successfully.'));
                return $this->controller->redirect(['back-office/brands']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to create product.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}