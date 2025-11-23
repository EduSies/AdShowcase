<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\brands;

use app\controllers\actions\back_office\BaseDataTableAction;
use app\models\Brand;
use Yii;
use yii\web\Response;

final class BrandDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = Brand::class;
    public ?string $view = '@app/views/back_office/brands/index';

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