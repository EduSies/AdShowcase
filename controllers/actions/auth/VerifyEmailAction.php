<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use Yii;
use yii\web\BadRequestHttpException;
use app\models\auth\VerifyEmailForm;

final class VerifyEmailAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';

    public function run(string $token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (\Throwable $e) {
            throw new BadRequestHttpException('El token no es válido o ha caducado.');
        }

        if (($user = $model->verifyEmail()) !== null) {
            Yii::$app->user->login($user);
            Yii::$app->session->setFlash('success', 'Email verificado. ¡Bienvenido!');
            return $this->controller->goHome();
        }

        Yii::$app->session->setFlash('error', 'No se pudo verificar el email.');
        return $this->controller->redirect(['auth/login']);
    }
}