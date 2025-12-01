<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\country;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Country;
use app\services\back_office\country\BackOfficeCountryDeleteService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class CountryDeleteAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public string $idParam = 'hash';
    public ?string $modelClass = Country::class;
    public ?string $view = '@app/views/back_office/country/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $service = new BackOfficeCountryDeleteService();
        $ok = $service->delete($hash);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'success' => $ok,
                'message' => $ok
                    ? Yii::t('app', 'Deleted successfully.')
                    : Yii::t('app', 'Delete failed.'),
            ];
        }

        Yii::$app->session->setFlash(
            $ok ? 'success' : 'error',
            $ok ? Yii::t('app', 'Deleted successfully.') : Yii::t('app', 'Delete failed.')
        );

        return $this->controller->redirect(['back-office/countries']);
    }
}