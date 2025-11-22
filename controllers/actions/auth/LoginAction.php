<?php

namespace app\controllers\actions\auth;

use app\models\forms\auth\LoginForm;
use Yii;
use yii\base\Action;

class LoginAction extends Action
{
    public ?string $successUrl = null;

    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->controller->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->controller->goBack($this->successUrl ?? (Yii::$app->user->getReturnUrl() ?: Yii::$app->homeUrl));
        }

        $model->password = '';

        return $this->controller->render($model->formName(), [
            'model' => $model,
            'title' => Yii::t('app', 'Login'),
        ]);
    }
}