<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\sales_type;

use app\controllers\actions\back_office\BaseDataTableAction;
use app\models\SalesType;
use Yii;
use yii\web\Response;

final class SalesTypeDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = SalesType::class;
    public ?string $view = '@app/views/back_office/sales_type/index';

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