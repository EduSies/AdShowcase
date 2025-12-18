<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\brand;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Brand;
use app\models\forms\back_office\BrandForm;
use app\services\back_office\brand\BackOfficeBrandUpdateService;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;

final class BrandUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = BrandForm::class;
    public ?string $view = '@app/views/back_office/brand/' . BrandForm::FORM_NAME;
    public ?array $indexRoute = ['/back-office/brands'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $class = $this->modelClass;
        $brand = Brand::findOne(['hash' => $hash]);

        if (!$brand) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => BrandForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => $brand->id,
            'hash' => $brand->hash,
            'name' => $brand->name,
            'url_slug' => $brand->url_slug,
            'status' => $brand->status,
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeBrandUpdateService();

            if ($service->update($brand->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update brand.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'status' => \app\helpers\StatusHelper::statusFilter(3),
        ]);
    }
}