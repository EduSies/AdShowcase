<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\agencies;

use app\controllers\actions\back_office\BaseDataTableAction;
use app\models\Agency;
use Yii;
use yii\web\Response;

final class AgencyDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = Agency::class;
    public ?string $view = '@app/views/back-office/agencies/index';

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