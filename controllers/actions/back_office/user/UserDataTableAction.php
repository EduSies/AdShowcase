<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\user;

use app\controllers\actions\back_office\BaseDataTableAction;
use app\models\User;
use Yii;
use yii\web\Response;

final class UserDataTableAction extends BaseDataTableAction
{
    public ?string $can = 'users.manage';
    public ?string $modelClass = User::class;
    public ?string $view = '@app/views/back_office/user/index';

    public function run()
    {
        $this->ensureCan($this->can);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'data' => [],
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'draw' => (int) Yii::$app->request->get('draw', 1),
        ];
    }
}