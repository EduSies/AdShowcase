<?php

declare(strict_types=1);

namespace app\controllers\back_office;

use app\controllers\BaseWebController;

class CreativeController extends BaseWebController
{
    public function actions(): array
    {
        return [
            'index' => \app\controllers\actions\back_office\creative\CreativeIndexAction::class,
            'create' => \app\controllers\actions\back_office\creative\CreativeCreateAction::class,
            'update' => \app\controllers\actions\back_office\creative\CreativeUpdateAction::class,
            'delete' => \app\controllers\actions\back_office\creative\CreativeDeleteAction::class,
        ];
    }
}