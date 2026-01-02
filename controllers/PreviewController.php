<?php

namespace app\controllers;

use yii\filters\AccessControl;

class PreviewController extends BaseWebController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                // Acción 'preview'
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['@'],
                ],
                // Acción 'mockup'
                [
                    'allow' => true,
                    'actions' => ['mockup'],
                    'roles' => ['?', '@'], // '?' = Invitados, '@' = Logueados (Todos)
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            // ===== Dashboard =====
            'index' => [
                'class' => \app\controllers\actions\preview\PreviewIndexAction::class,
            ],
            // ===== Mockup Preview =====
            'mockup' => [
                'class' => \app\controllers\actions\preview\PreviewMockupAction::class,
            ]
        ]);
    }
}