<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;

class SharedLinkController extends BaseWebController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                // Acción 'open'
                [
                    'allow' => true,
                    'actions' => ['open'],
                    'roles' => ['?', '@'], // '?' = Invitados, '@' = Logueados (Todos)
                ],
                // Acción 'generate'
                [
                    'allow' => true,
                    'actions' => ['generate'],
                    'roles' => ['@'], // Solo usuarios logueados
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'generate' => [
                'class' => \app\controllers\actions\shared_link\GenerateLinkAction::class,
            ],
            'open' => [
                'class' => \app\controllers\actions\shared_link\OpenLinkAction::class,
            ]
        ]);
    }
}