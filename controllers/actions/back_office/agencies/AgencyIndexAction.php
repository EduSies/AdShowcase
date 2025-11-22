<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\agencies;

use app\controllers\actions\back_office\BaseBackOfficeAction;

final class AgencyIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $view = '@app/views/backoffice/agencies/index';

    public function run()
    {
        $this->ensureCan($this->can);
        // Renderiza la vista de listado (DataTable/filters/…)
        return $this->controller->render($this->view ?? 'index');
    }
}