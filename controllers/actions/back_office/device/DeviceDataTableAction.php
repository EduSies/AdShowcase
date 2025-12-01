<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\device;

use app\controllers\actions\back_office\BaseDataTableAction;
use app\models\Device;
use Yii;
use yii\web\Response;

final class DeviceDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $modelClass = Device::class;
    public ?string $view = '@app/views/back_office/device/index';

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