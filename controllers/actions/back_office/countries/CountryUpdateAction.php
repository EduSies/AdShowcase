<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\countries;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Country;
use app\models\forms\back_office\CountryForm;
use app\services\back_office\country\BackOfficeCountryUpdateService;
use Yii;
use yii\web\NotFoundHttpException;

final class CountryUpdateAction extends BaseBackOfficeAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $modelClass = CountryForm::class;
    public ?string $view = '@app/views/back_office/countries/' . CountryForm::FORM_NAME;
    public string $idParam = 'iso';

    public function run()
    {
        $this->ensureCan($this->can);

        $id = (string)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing id.'));
        }

        $class = $this->modelClass;

        $country = Country::findOne($id);
        if (!$country) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => CountryForm::SCENARIO_UPDATE]);
//        $model->setAttributes([
//            'id'       => (int)$country->id,
//            'hash'     => $country->hash,
//            'name'     => $country->name,
//            'url_name' => $country->url_name,
//            'status'   => $country->status,
//        ], false);

        if ($model->load(Yii::$app->request->post())) {
            $service = new BackOfficeCountryUpdateService();

            if ($service->update($country->id, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect(['back-office/countries']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update country.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}