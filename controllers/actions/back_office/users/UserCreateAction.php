<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\users;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use Yii;

final class UserCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'users.manage';
    public ?string $modelClass = \app\models\User::class;
    public ?string $view = '@app/views/back_office/users/create';

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