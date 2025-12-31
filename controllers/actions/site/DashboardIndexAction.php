<?php

declare(strict_types=1);

namespace app\controllers\actions\site;

use Yii;

final class DashboardIndexAction extends BaseSiteAction
{
    public ?string $layout = 'main-backoffice';
    public ?string $view = '@app/views/site/index';

    public function run()
    {
        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Dashboard'),
            'sections' => $this->sections,
        ]);
    }
}