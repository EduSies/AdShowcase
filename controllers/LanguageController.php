<?php

declare(strict_types=1);

namespace app\controllers;

class LanguageController extends BaseWebController
{
    public function behaviors(): array
    {
        // 1. Heredamos comportamientos del padre (que bloquea accesos)
        $behaviors = parent::behaviors();

        // 2. AÑADIR EXCEPCIÓN: Permitimos la acción 'change' a todo el mundo
        // Esto evita que te redirija al login si eres un invitado.
        $behaviors['access']['except'] = ['change'];

        return $behaviors;
    }

    public function actions(): array
    {
        return [
            // ===== Language =====
            'change' => [
                'class' => \app\controllers\actions\language\ChangeAction::class,
            ],
        ];
    }
}