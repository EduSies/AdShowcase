<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\sales_types;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\services\back_office\salesType\BackOfficeSalesTypeListService;
use Yii;

final class SalesTypeIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/sales_types/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeSalesTypeListService())->findAll();

        return $this->controller->render($this->view, [
            'title' => Yii::t('app','Sales Types list'),
            'rows'  => $rows,
        ]);
    }
}