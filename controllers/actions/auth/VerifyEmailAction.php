<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use app\services\auth\AuthService;
use Yii;
use yii\web\BadRequestHttpException;

final class VerifyEmailAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';

    public function run(string $token)
    {
        $service = new AuthService();

        try {
            $user = $service->verifyEmailToken($token);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($user !== null) {
            // Esto permite que el usuario use la pantalla de "Reset Password" para crear su clave
            $service->generatePasswordResetToken($user);

            // Guardamos el usuario con el nuevo token de reset
            $user->save(false, ['password_reset_token']);

            Yii::$app->session->setFlash('success', Yii::t('app', 'Email verified. Please set your new password.'));

            return $this->controller->redirect(['auth/reset-password', 'token' => $user->password_reset_token]);
        }

        Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to verify your account with provided token.'));

        return $this->controller->redirect(['auth/login']);
    }
}