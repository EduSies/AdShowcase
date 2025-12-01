<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;

final class BackOfficeUserDeleteService
{
    public function delete(string $hash): bool
    {
        $user = User::findOne(['hash' => $hash]);

        if (!$user) {
            $user->addErrors($user->getErrors());
            return false;
        }

        // 1) Intentar borrar el usuario
        if ($user->delete() === false) {
            $user->addErrors($user->getErrors());
            return false;
        }

        // 2) Limpiar permisos asociados a ese user_id
        if (isset(\Yii::$app->authManager)) {
            \Yii::$app->authManager->revokeAll($user->id);
        }

        return true;
    }
}