<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use app\models\forms\auth\LoginForm;
use app\services\auth\AuthLoginService;
use Yii;
use yii\bootstrap5\ActiveForm;

final class LoginAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';
    public ?string $successUrl = null;

    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->controller->goHome();
        }

        $model = new LoginForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new AuthLoginService();

            if ($service->loginAttempt($model)) {
                return $this->controller->goBack($this->successUrl ?? (Yii::$app->user->getReturnUrl() ?: Yii::$app->homeUrl));
            }
        }

        $model->password = '';

        return $this->controller->render($model->formName(), [
            'model' => $model,
            'title' => Yii::t('app', 'Login'),
        ]);
    }
}