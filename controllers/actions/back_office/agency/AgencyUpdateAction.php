<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\agency;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Agency;
use app\models\forms\back_office\AgencyForm;
use app\services\back_office\agency\BackOfficeAgencyUpdateService;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;

final class AgencyUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = AgencyForm::class;
    public ?string $view = '@app/views/back_office/agency/' . AgencyForm::FORM_NAME;
    public ?array $indexRoute = ['back-office/agencies'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $class = $this->modelClass;
        $agency = Agency::findOne(['hash' => $hash]);

        if (!$agency) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => AgencyForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => $agency->id,
            'hash' => $agency->hash,
            'name' => $agency->name,
            'country_id' => $agency->country_id,
            'status' => $agency->status,
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeAgencyUpdateService();

            if ($service->update($agency->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update agency.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
        ]);
    }
}