<?php

namespace app\controllers\actions\auth;

use yii\base\Action;
use Yii;

class LogoutAction extends Action
{
    public function run()
    {
        Yii::$app->user->logout();
        return $this->controller->goHome();
    }
}