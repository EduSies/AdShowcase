<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\users;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use Yii;

final class UserIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'users.manage';
    public ?string $view = '@app/views/back_office/users/index';

    public function run()
    {
        $this->ensureCan($this->can);
        // Renderiza la vista de listado (DataTable/filters/…)



        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Users list'),
        ]);
    }
}