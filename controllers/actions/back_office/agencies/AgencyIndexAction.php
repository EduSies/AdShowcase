<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\agencies;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\services\back_office\agencies\BackOfficeAgencyListService;
use Yii;

final class AgencyIndexAction extends BaseBackOfficeAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/agencies/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeAgencyListService)->findAll();

        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Agencies list'),
            'rows' => $rows,
        ]);
    }
}