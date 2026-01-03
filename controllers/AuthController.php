<?php

declare(strict_types=1);

namespace app\controllers;

use app\controllers\actions\auth\LoginAction;
use app\controllers\actions\auth\LogoutAction;
use app\controllers\actions\auth\RequestPasswordResetAction;
use app\controllers\actions\auth\ResetPasswordAction;
use app\controllers\actions\auth\VerifyEmailAction;
use yii\filters\VerbFilter;

class AuthController extends BaseWebController
{
    public $layout = 'login-layout';

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'logout' => ['get','post'],
            ],
        ];

        $behaviors['access']['except'] = [
            'login',
            'request-password-reset',
            'reset-password',
            'verify-email',
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'login' => [
                'class' => LoginAction::class,
                'successUrl' => \Yii::$app->homeUrl,
            ],
            'logout' => [
                'class' => LogoutAction::class,
            ],
            'request-password-reset' => [
                'class' => RequestPasswordResetAction::class,
            ],
            'reset-password' => [
                'class' => ResetPasswordAction::class,
            ],
            'verify-email' => [
                'class' => VerifyEmailAction::class,
            ],
        ]);
    }
}