<?php

namespace app\controllers\actions\auth;

use yii\base\Action;
use Yii;
use yii\web\BadRequestHttpException;
use app\models\auth\ResetPasswordForm;

class ResetPasswordAction extends Action
{
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