<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\countries;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use Yii;
use yii\web\NotFoundHttpException;

final class CountryUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = \app\models\Country::class;
    public ?string $view = '@app/views/back_office/countries/update';

    /** Nombre del parámetro que trae el ID (GET/POST). */
    public string $idParam = 'id';

    public function run()
    {
        $this->ensureCan($this->can);
        $id = (string)Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($id === '') {
            throw new NotFoundHttpException('Missing id.');
        }

        $class = $this->modelClass;
        /** @var \yii\db\ActiveRecord|null $model */
        $model = $class::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Not found.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
            return $this->controller->redirect(['backoffice/' . $this->controller->action->id]); // ajusta destino
        }

        return $this->controller->render($this->view ?? 'update', ['model' => $model]);
    }
}