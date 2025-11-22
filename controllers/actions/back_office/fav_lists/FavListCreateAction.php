<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\fav_lists;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use Yii;

final class FavListCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'favorite.manage';
    public ?string $modelClass = \app\models\FavList::class;
    public ?string $view = '@app/views/back_office/fav_lists/create';

    public function run()
    {
        $this->ensureCan($this->can);
        $class = $this->modelClass;
        /** @var \yii\db\ActiveRecord $model */
        $model = new $class();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created successfully.'));
            return $this->controller->redirect(['backoffice/' . $this->controller->action->id]); // ajusta si quieres volver a index
        }

        return $this->controller->render($this->view ?? 'create', ['model' => $model]);
    }
}