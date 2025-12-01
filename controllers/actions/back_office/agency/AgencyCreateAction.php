<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\agency;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\AgencyForm;
use app\services\back_office\agency\BackOfficeAgencyCreateService;
use Yii;

final class AgencyCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = AgencyForm::class;
    public ?string $view = '@app/views/back_office/agency/' . AgencyForm::FORM_NAME;

    public function run()
    {
        $this->ensureCan($this->can);

        $class = $this->modelClass;
        $model = new $class(['scenario' => AgencyForm::SCENARIO_CREATE]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeAgencyCreateService();
            $ok = $service->create($model);

            if ($ok) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Created successfully.'));
                return $this->controller->redirect(['back-office/agencies']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to create agency.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}