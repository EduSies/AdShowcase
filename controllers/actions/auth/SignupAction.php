<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use Yii;
use app\models\auth\SignupForm;

final class SignupAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';

    public function run()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && ($user = $model->signup())) {
            Yii::$app->session->setFlash('success', 'Cuenta creada. Revisa tu email para verificarla.');
            return $this->controller->redirect(['auth/login']);
        }
        return $this->controller->render('signup', ['model' => $model]);
    }
}