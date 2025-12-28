<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\creative;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\helpers\LangHelper;
use app\helpers\StatusHelper;
use app\models\Creative;
use app\models\forms\back_office\CreativeForm;
use app\services\back_office\agency\BackOfficeAgencyListService;
use app\services\back_office\brand\BackOfficeBrandListService;
use app\services\back_office\country\BackOfficeCountryListService;
use app\services\back_office\creative\BackOfficeCreativeUpdateService;
use app\services\back_office\device\BackOfficeDeviceListService;
use app\services\back_office\format\BackOfficeFormatListService;
use app\services\back_office\product\BackOfficeProductListService;
use app\services\back_office\sales_type\BackOfficeSalesTypeListService;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;

final class CreativeUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'creative.manage';
    public ?string $modelClass = CreativeForm::class;
    public ?string $view = '@app/views/back_office/creative/' . CreativeForm::FORM_NAME;
    public ?array $indexRoute = ['/back-office/creatives'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException('Missing hash.');
        }

        $class = $this->modelClass;
        $creative = Creative::findOne(['hash' => $hash]);

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
            'language_id' => $creative->language_id,
            'click_url' => $creative->click_url,
            'workflow_status' => $creative->workflow_status,
            'status' => $creative->status,
        ]);

        if ($creative->assetFile) {
            $model->preview_asset_url = $creative->assetFile->storage_path;
            $model->preview_asset_mime = $creative->assetFile->mime;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
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
            'brands' => (new BackOfficeBrandListService())->getBrandsDropDown(),
            'agencies' => (new BackOfficeAgencyListService())->getAgenciesDropDown(),
            'products' => (new BackOfficeProductListService())->getProductsDropDown(),
            'formats' => (new BackOfficeFormatListService())->getFormatsDropDown(),
            'devices' => (new BackOfficeDeviceListService())->getDevicesDropDown(),
            'salesTypes' => (new BackOfficeSalesTypeListService())->getSalesTypesDropDown(),
            'languages' => LangHelper::getLanguageOptions(),
            'countries' => (new BackOfficeCountryListService())->getCountriesDropDown(),
            'status' => StatusHelper::statusFilter(3),
            'workflowStatus' => StatusHelper::workflowStatusFilter(),
        ]);
    }
}