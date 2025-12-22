<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;

final class BackOfficeUserDeleteService
{
    use UserAssetTrait;

    public function delete(string $hash): bool
    {
        $user = User::findOne(['hash' => $hash]);

        if (!$user) {
            return false;
        }

        // Capturar datos antes de borrar
        $avatarUrl = $user->avatar_url;

        if ($user->delete() === false) {
            return false;
        }

        // Limpiar permisos asociados a ese user_id
        if (isset(\Yii::$app->authManager)) {
            \Yii::$app->authManager->revokeAll($user->id);
        }

        // Limpieza de archivos físicos
        try {
            if ($avatarUrl) {
                $this->deleteOrphanedAvatar($avatarUrl);
            }
        } catch (\Exception $e) {
            \Yii::error('Error cleaning up user avatar: ' . $e->getMessage());
        }

        return true;
    }
}