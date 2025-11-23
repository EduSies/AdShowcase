<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\agencies;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Agency;
use app\models\forms\back_office\AgencyForm;
use app\services\back_office\agencies\BackOfficeAgencyUpdateService;
use Yii;
use yii\web\NotFoundHttpException;

final class AgencyUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = AgencyForm::class;
    public ?string $view = '@app/views/back_office/agencies/' . AgencyForm::FORM_NAME;
    public string $idParam = 'id';

    public function run()
    {
        $this->ensureCan($this->can);

        $id = (string)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing id.'));
        }

        $class = $this->modelClass;

        $agency = Agency::findOne($id);
        if (!$agency) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => AgencyForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => (int)$agency->id,
            'hash' => $agency->hash,
            'name' => $agency->name,
            'country_id' => $agency->country_id,
            'status' => $agency->status,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $service = new BackOfficeAgencyUpdateService();

            if ($service->update($agency->id, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect(['back-office/brands']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update agency.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}