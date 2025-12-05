<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\country;

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
    public ?string $view = '@app/views/back_office/country/' . CountryForm::FORM_NAME;
    public ?array $indexRoute = ['/back-office/countries'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $class = $this->modelClass;
        $country = Country::findOne(['hash' => $hash]);

        if (!$country) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => CountryForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => $country->id,
            'hash' => $country->hash,
            'iso' => $country->iso,
            'iso3' => $country->iso3,
            'name' => $country->name,
            'continent_code' => $country->continent_code,
            'currency_code' => $country->currency_code,
            'status' => $country->status,
            'url_slug' => $country->url_slug,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeCountryUpdateService();

            if ($service->update($country->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update country.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
        ]);
    }
}