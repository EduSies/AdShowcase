<?php

declare(strict_types=1);

namespace app\controllers;

use app\helpers\MenuHelper;
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
                'denyCallback' => function () {
                    if (Yii::$app->user->isGuest) {
                        return Yii::$app->response->redirect(['auth/login']);
                    }
                    throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
                },
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actions()
    {
        $this->layout = '@adshowcase/views/layouts/main-backoffice';

        return array_merge(parent::actions(), [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
                'view' => '@adshowcase/views/layouts/error',
                'layout' => '@adshowcase/views/layouts/login-layout',
            ],
        ]);
    }

    /**
     * Proxy al Helper para mantener compatibilidad con las vistas
     */
    public function getSectionsMenu(): array
    {
        return MenuHelper::getBackOfficeMenu();
    }
}