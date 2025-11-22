<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\countries;

use app\controllers\actions\back_office\BaseBackOfficeAction;

final class CountryIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/countries/index';

    public function run()
    {
        $this->ensureCan($this->can);
        // Renderiza la vista de listado (DataTable/filters/…)
        return $this->controller->render($this->view ?? 'index');
    }
}