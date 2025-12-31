<?php

namespace app\controllers;

class PreviewController extends BaseWebController
{
    public function actions(): array
    {
        return [
            // ===== Dashboard =====
            'index' => [
                'class' => \app\controllers\actions\preview\PreviewIndexAction::class,
            ],
            // ===== Mockup Preview =====
            'mockup' => [
                'class' => \app\controllers\actions\preview\PreviewMockupAction::class,
            ]
        ];
    }
}