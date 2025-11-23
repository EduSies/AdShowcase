<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\audit;

use app\controllers\actions\back_office\BaseDataTableAction;
use Yii;
use yii\web\Response;

final class AuditDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'audit.view';
    public ?string $modelClass = \app\models\AuditLog::class;
    public ?string $view = '@app/views/back_office/audit/index';

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