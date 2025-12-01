<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\creative;

use app\controllers\actions\back_office\BaseDataTableAction;
use Yii;
use yii\web\Response;

final class CreativeDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'creative.view';
    public ?string $modelClass = \app\models\Creative::class;
    public ?string $view = '@app/views/back_office/creatives/index';

    public function run()
    {
        $this->ensureCan($this->can);

        Yii::$app->response->format = Response::FORMAT_JSON;
        // TODO: Implementar query real (filtros, orden, paginación)
        return [
            'data' => [],
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'draw' => (int)Yii::$app->request->get('draw', 1),
        ];
    }
}