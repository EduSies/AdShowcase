<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\countries;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\CountryForm;
use app\services\back_office\country\BackOfficeCountryCreateService;
use Yii;

final class CountryCreateAction extends BaseBackOfficeAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $modelClass = CountryForm::class;
    public ?string $view = '@app/views/back_office/countries/' . CountryForm::FORM_NAME;

    public function run()
    {
        $this->ensureCan($this->can);

        $class = $this->modelClass;
        $model = new $class(['scenario' => CountryForm::SCENARIO_CREATE]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeCountryCreateService();
            $ok = $service->create($model);

            if ($ok) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Created successfully.'));
                return $this->controller->redirect(['back-office/countries']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to create country.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}