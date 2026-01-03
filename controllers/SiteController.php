<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class SiteController extends BaseWebController
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                // CATÁLOGO (Home)
                // Permitimos entrar si tiene el permiso básico 'creative.view'
                // Roles: Viewer, Sales, Editor, Admin
                [
                    'actions' => ['catalog', 'index'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function () {
                        return Yii::$app->user->can('creative.view');
                    }
                ],
                // DASHBOARD
                // Permitimos entrar SOLO si tiene 'backoffice.access'
                // Roles: Sales, Editor, Admin
                [
                    'actions' => ['dashboard'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function () {
                        return Yii::$app->user->can('backoffice.access');
                    }
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                if (Yii::$app->user->isGuest) {
                    return Yii::$app->response->redirect(['auth/login']);
                }

                throw new ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
            }
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'dashboard' => [
                'class' => \app\controllers\actions\site\DashboardIndexAction::class,
                'sections' => $this->getSectionsMenu(),
            ],
            'catalog' => [
                'class' => \app\controllers\actions\site\CatalogIndexAction::class,
                'sections' => $this->getSectionsMenu(),
            ],
        ]);
    }
}