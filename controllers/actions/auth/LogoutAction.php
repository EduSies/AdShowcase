<?php

namespace app\controllers\actions\auth;

use yii\base\Action;
use Yii;

class LogoutAction extends Action
{
    public function run()
    {
        // 1. Borramos la preferencia de idioma de la sesión actual
        Yii::$app->session->remove('_lang');

        // 2. Cerramos la sesión del usuario
        Yii::$app->user->logout();

        // 3. Redirigimos al home (donde se ejecutará de nuevo la detección automática)
        return $this->controller->goHome();
    }
}