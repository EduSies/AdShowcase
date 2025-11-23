<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\products;

use app\controllers\actions\back_office\BaseDataTableAction;
use app\models\Product;
use Yii;
use yii\web\Response;

final class ProductDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = Product::class;
    public ?string $view = '@app/views/back_office/products/index';

    public function run()
    {
        $this->ensureCan($this->can);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'data' => [],
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'draw' => (int)Yii::$app->request->get('draw', 1),
        ];
    }
}