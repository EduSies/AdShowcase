<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\brands;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Brand;
use app\models\forms\back_office\BrandForm;
use app\services\back_office\brand\BackOfficeBrandUpdateService;
use Yii;
use yii\web\NotFoundHttpException;

final class BrandUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = BrandForm::class;
    public ?string $view = '@app/views/back_office/brands/' . BrandForm::FORM_MANE;

    /** Nombre del parámetro que trae el ID (GET/POST). */
    public string $idParam = 'id';

    public function run()
    {
        $this->ensureCan($this->can);

        $id = (string)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing id.'));
        }

        $class = $this->modelClass;

        $brand = Brand::findOne($id);
        if (!$brand) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => BrandForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id'       => (int)$brand->id,
            'hash'     => $brand->hash,
            'name'     => $brand->name,
            'url_name' => $brand->url_name,
            'status'   => $brand->status,
        ], false);

        if ($model->load(Yii::$app->request->post())) {
            $service = new BackOfficeBrandUpdateService();

            if ($service->update($brand->id, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                // Ajusta el destino de vuelta a tu índice/listado
                return $this->controller->redirect(['back-office/brands']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update brand.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}