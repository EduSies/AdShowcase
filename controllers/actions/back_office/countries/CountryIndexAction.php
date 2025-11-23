<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\countries;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\services\back_office\country\BackOfficeCountryListService;
use Yii;

final class CountryIndexAction extends BaseBackOfficeAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/countries/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeCountryListService)->findAll();

        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Countries list'),
            'rows' => $rows,
        ]);
    }
}