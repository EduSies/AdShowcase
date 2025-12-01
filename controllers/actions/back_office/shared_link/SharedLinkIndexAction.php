<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\shared_link;

use app\controllers\actions\back_office\BaseBackOfficeAction;

final class SharedLinkIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'share.manage';
    public ?string $view = '@app/views/back_office/shared_links/index';

    public function run()
    {
        $this->ensureCan($this->can);
        // Renderiza la vista de listado (DataTable/filters/…)
        return $this->controller->render($this->view ?? 'index');
    }
}