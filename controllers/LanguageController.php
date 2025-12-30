<?php

declare(strict_types=1);

namespace app\controllers;

class LanguageController extends BaseWebController
{
    public function behaviors(): array
    {
        // Heredamos comportamientos del padre (que bloquea accesos)
        $behaviors = parent::behaviors();

        // Esto evita que te redirija al login si eres un invitado.
        $behaviors['access']['except'] = ['change'];

        return $behaviors;
    }

    public function actions(): array
    {
        return [
            'change' => [
                'class' => \app\controllers\actions\language\ChangeAction::class,
            ],
        ];
    }
}