<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\formats;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\services\back_office\format\BackOfficeFormatListService;
use Yii;

final class FormatIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/formats/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeFormatListService)->findAll();

        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Formats list'),
            'rows' => $rows,
        ]);
    }
}