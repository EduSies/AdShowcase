<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use Yii;

final class LogoutAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';

    public function run()
    {
        // Borramos la preferencia de idioma de la sesión actual
        Yii::$app->session->remove('_lang');

        // Cerramos la sesión del usuario
        Yii::$app->user->logout();

        // Redirigimos al home (donde se ejecutará de nuevo la detección automática)
        return $this->controller->goHome();
    }
}