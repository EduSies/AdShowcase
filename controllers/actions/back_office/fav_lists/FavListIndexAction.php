<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\fav_lists;

use app\controllers\actions\back_office\BaseBackOfficeAction;

final class FavListIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'favorite.manage';
    public ?string $view = '@app/views/back_office/fav_lists/index';

    public function run()
    {
        $this->ensureCan($this->can);
        // Renderiza la vista de listado (DataTable/filters/…)
        return $this->controller->render($this->view ?? 'index');
    }
}