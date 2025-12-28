<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\creative;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\helpers\LangHelper;
use app\helpers\StatusHelper;
use app\models\forms\back_office\CreativeForm;
use app\services\back_office\agency\BackOfficeAgencyListService;
use app\services\back_office\brand\BackOfficeBrandListService;
use app\services\back_office\country\BackOfficeCountryListService;
use app\services\back_office\creative\BackOfficeCreativeCreateService;
use app\services\back_office\device\BackOfficeDeviceListService;
use app\services\back_office\format\BackOfficeFormatListService;
use app\services\back_office\product\BackOfficeProductListService;
use app\services\back_office\sales_type\BackOfficeSalesTypeListService;
use Yii;
use yii\bootstrap5\ActiveForm;

final class CreativeCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'creative.manage';
    public ?string $modelClass = CreativeForm::class;
    public ?string $view = '@app/views/back_office/creative/' . CreativeForm::FORM_NAME;
    public ?array $indexRoute = ['back-office/creatives'];

    public function run()
    {
        $this->ensureCan($this->can);

        $class = $this->modelClass;
        $model = new $class(['scenario' => CreativeForm::SCENARIO_CREATE]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post())) {
            $service = new BackOfficeCreativeCreateService();
            $ok = $service->create($model);

            if ($ok) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Created successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to create creative.');
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