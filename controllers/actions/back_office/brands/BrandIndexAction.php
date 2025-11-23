<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\brands;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\services\back_office\brand\BackOfficeBrandListService;
use Yii;

final class BrandIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/brands/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeBrandListService)->findAll();

        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Brands list'),
            'rows' => $rows,
        ]);
    }
}