<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\user;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\User;
use Yii;
use yii\web\Response;

final class UserAssignRoleAction extends BaseBackofficeAction
{
    public ?string $can = 'users.manage';

    public function run(): array
    {
        $this->ensureCan($this->can);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = (string) Yii::$app->request->post('user_id', '');
        $roleName = (string) Yii::$app->request->post('role', '');

        if ($userId === '' || $userId <= 0 || $roleName === '') {
            return [
                'success' => false,
                'error' => \Yii::t('app','Invalid params.'),
            ];
        }

        $user = User::findOne($userId);
        if ($user === null) {
            return [
                'success' => false,
                'error' => \Yii::t('app','User not found.'),
            ];
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        if (!$role) {
            return [
                'success' => false,
                'error' => \Yii::t('app','Invalid role')
            ];
        }

        // Revoca roles anteriores
        foreach ($auth->getAssignments($userId) as $assign) {
            $auth->revoke($auth->getRole($assign->roleName), $userId);
        }

        $auth->assign($role, $userId);

        return ['success' => true];
    }
}