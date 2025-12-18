<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\creative;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\helpers\LangHelper;
use app\helpers\StatusHelper;
use app\models\Creative;
use app\models\forms\back_office\CreativeForm;
use app\services\back_office\agency\AgencyListService;
use app\services\back_office\brand\BrandListService;
use app\services\back_office\country\CountryListService;
use app\services\back_office\creative\BackOfficeCreativeUpdateService;
use app\services\back_office\device\DeviceListService;
use app\services\back_office\format\FormatListService;
use app\services\back_office\sales_type\SalesTypeListService;
use Yii;
use yii\web\NotFoundHttpException;

final class CreativeUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'creative.manage';
    public ?string $modelClass = CreativeForm::class;
    public ?string $view = '@app/views/back_office/creative/' . CreativeForm::FORM_NAME;
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException('Missing hash.');
        }

        $class = $this->modelClass;
        $creative = Creative::findOne($hash);

        if (!$creative) {
            throw new NotFoundHttpException('Not found.');
        }

        $model = new $class(['scenario' => CreativeForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => $creative->id,
            'hash' => $creative->hash,
            'title' => $creative->title,
            'asset_file_id' => $creative->asset_file_id,
            'url_thumbnail' => $creative->url_thumbnail,
            'brand_id' => $creative->brand_id,
            'agency_id' => $creative->agency_id,
            'device_id' => $creative->device_id,
            'country_id' => $creative->country_id,
            'format_id' => $creative->format_id,
            'sales_type_id' => $creative->sales_type_id,
            'product_id' => $creative->product_id,
            'language_id' => $creative->language,
            'click_url' => $creative->click_url,
            'workflow_status' => $creative->workflow_status,
            'status' => $creative->status,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeCreativeUpdateService();

            if ($service->update($creative->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update creative.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'brands' => (new BrandListService())->getBrandsDropDown(),
            'agencies' => (new AgencyListService())->getAgenciesDropDown(),
            'formats' => (new FormatListService())->getFormatsDropDown(),
            'devices' => (new DeviceListService())->getDevicesDropDown(),
            'salesTypes' => (new SalesTypeListService())->getSalesTypesDropDown(),
            'languages' => LangHelper::getLanguageOptions(),
            'countries' => (new CountryListService())->getCountriesDropDown(),
            'status' => StatusHelper::statusFilter(3),
            'workflowStatus' => StatusHelper::workflowStatusFilter(),
        ]);
    }
}