<?php

namespace app\controllers;

use app\controllers\actions\favorite\GetDropdownAction;

class FavoriteController extends BaseWebController
{
    public function actions(): array
    {
        return [
            'index' => [
                'class' => \app\controllers\actions\favorite\FavoriteIndexAction::class,
            ],
            'detail' => [
                'class' => \app\controllers\actions\favorite\FavoriteIndexAction::class,
            ],
            'get-dropdown' => [
                'class' => \app\controllers\actions\favorite\GetDropdownAction::class,
            ],
            'create-list' => [
                'class' => \app\controllers\actions\favorite\CreateListAction::class,
            ],
            'toggle-item' => [
                'class' => \app\controllers\actions\favorite\ToggleItemAction::class,
            ],
        ];
    }
}