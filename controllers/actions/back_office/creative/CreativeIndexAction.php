<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\creative;

use app\controllers\actions\back_office\BaseBackOfficeAction;

final class CreativeIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'creative.view';
    public ?string $view = '@app/views/back_office/creatives/index';

    public function run()
    {
        $this->ensureCan($this->can);
        // Renderiza la vista de listado (DataTable/filters/…)
        return $this->controller->render($this->view ?? 'index');
    }
}