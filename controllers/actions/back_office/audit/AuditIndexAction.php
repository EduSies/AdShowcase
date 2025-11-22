<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\audit;

use app\controllers\actions\back_office\BaseBackOfficeAction;

final class AuditIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'audit.view';
    public ?string $view = '@app/views/back_office/audit/index';

    public function run()
    {
        $this->ensureCan($this->can);
        // Renderiza la vista de listado (DataTable/filters/…)
        return $this->controller->render($this->view ?? 'index');
    }
}