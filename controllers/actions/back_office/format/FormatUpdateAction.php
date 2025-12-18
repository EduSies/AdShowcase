<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\format;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Format;
use app\models\Forms\back_office\FormatForm;
use app\services\back_office\format\BackOfficeFormatUpdateService;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;

final class FormatUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = FormatForm::class;
    public ?string $view = '@app/views/back_office/format/' . FormatForm::FORM_NAME;
    public ?array $indexRoute = ['/back-office/formats'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $class = $this->modelClass;
        $format = Format::findOne(['hash' => $hash]);

        if (!$format) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => FormatForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => $format->id,
            'hash' => $format->hash,
            'name' => $format->name,
            'format' => $format->format,
            'family' => $format->family,
            'experience' => $format->experience,
            'subtype' => $format->subtype,
            'status' => $format->status,
            'url_slug' => $format->url_slug,
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeFormatUpdateService();

            if ($service->update($format->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update format.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'status' => \app\helpers\StatusHelper::statusFilter(3),
        ]);
    }
}