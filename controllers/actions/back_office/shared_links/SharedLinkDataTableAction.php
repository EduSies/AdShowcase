<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\shared_links;

use app\controllers\actions\back_office\BaseDataTableAction;
use Yii;
use yii\web\Response;

final class SharedLinkDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'share.manage';
    public ?string $modelClass = \app\models\SharedLink::class;
    public ?string $view = '@app/views/back_office/shared_links/index';

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