<?php
declare(strict_types=1);
namespace app\controllers\actions\back_office\products;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\services\back_office\product\BackOfficeProductListService;
use Yii;

final class ProductIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/products/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeProductListService())->findAll();

        return $this->controller->render($this->view, [
            'title' => Yii::t('app','Products list'),
            'rows'  => $rows,
        ]);
    }
}
