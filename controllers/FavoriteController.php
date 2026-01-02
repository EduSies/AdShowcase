<?php

namespace app\controllers;

use app\controllers\actions\favorite\GetDropdownAction;

class FavoriteController extends BaseWebController
{
    public function actions(): array
    {
        return array_merge(parent::actions(), [
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
            'update-list' => [
                'class' => \app\controllers\actions\favorite\UpdateListAction::class
            ],
            'move-list' => [
                'class' => \app\controllers\actions\favorite\MoveListAction::class,
            ],
            'delete-list' => [
                'class' => \app\controllers\actions\favorite\DeleteListAction::class,
            ],
        ]);
    }
}