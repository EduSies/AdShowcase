<?php

namespace app\controllers;

class SharedController extends BaseWebController
{
    public function actions(): array
    {
        return [
            // ===== Dashboard =====
            'index' => [
                'class' => \app\controllers\actions\shared\DashboardIndexAction::class,
            ],
            'catalog' => [
                'class' => \app\controllers\actions\shared\CatalogIndexAction::class,
            ],
        ];
    }
}