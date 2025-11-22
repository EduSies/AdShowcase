<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class BaseWebController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function() {
                    if (Yii::$app->user->isGuest) {
                        return Yii::$app->response->redirect(['auth/login']);
                    }
                    throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
                },
                'rules' => [
                    // Todo requiere estar logueado
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actions()
    {
        $this->layout = '@adshowcase/views/layouts/main';

        return array_merge(parent::actions(), [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
                'view' => '@adshowcase/views/layouts/error',
                //'layout' => '@adshowcase/views/layouts/layout-error'
            ],
        ]);
    }
}