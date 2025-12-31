<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use Yii;
use app\models\auth\PasswordResetRequestForm;

final class RequestPasswordResetAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';

    public function run()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Te enviamos un email con instrucciones de reseteo.');
                return $this->controller->redirect(['auth/login']);
            }
            Yii::$app->session->setFlash('error', 'No se pudo enviar el email.');
        }
        return $this->controller->render('requestPasswordResetToken', ['model' => $model]);
    }
}