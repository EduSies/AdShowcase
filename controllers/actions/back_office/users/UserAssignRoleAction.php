<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\users;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use Yii;
use yii\web\Response;

final class UserAssignRoleAction extends BaseBackofficeAction
{
    public ?string $can = 'users.manage';

    public function run(): array
    {
        $this->ensureCan($this->can);
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = (string)Yii::$app->request->post('user_id', '');
        $roleName = (string)Yii::$app->request->post('role', '');
        if ($userId === '' || $roleName === '') {
            return ['success' => false, 'error' => 'Missing params'];
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        if (!$role) {
            return ['success' => false, 'error' => 'Invalid role'];
        }

        // Revoca roles anteriores si tu política lo exige (opcional).
        foreach ($auth->getAssignments($userId) as $assign) {
            $auth->revoke($auth->getRole($assign->roleName), $userId);
        }

        $auth->assign($role, $userId);
        return ['success' => true];
    }
}