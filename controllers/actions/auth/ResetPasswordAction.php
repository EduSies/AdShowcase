<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use Yii;
use yii\web\BadRequestHttpException;
use app\models\auth\ResetPasswordForm;

final class ResetPasswordAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';

    public function run(string $token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (\Throwable $e) {
            throw new BadRequestHttpException('El token no es válido o ha caducado.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Contraseña restablecida.');
            return $this->controller->redirect(['auth/login']);
        }

        return $this->controller->render('resetPassword', ['model' => $model]);
    }
}