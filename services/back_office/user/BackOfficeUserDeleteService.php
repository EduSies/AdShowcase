<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;
use app\models\Creative;
use Yii;

final class BackOfficeUserDeleteService
{
    use UserAssetTrait;

    public function delete(string $hash): bool
    {
        $user = User::findOne(['hash' => $hash]);

        if (!$user) {
            return false;
        }

        // Iniciamos una transacción para asegurar la integridad
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Capturar datos necesarios antes de borrar
            $avatarUrl = $user->avatar_url;
            $userId = $user->id;

            // Borrar dependencias a las creatividades
            $creatives = Creative::find()->where(['user_id' => $userId])->all();
            foreach ($creatives as $creative) {
                if (!$creative->delete()) {
                    throw new \Exception(Yii::t('app', 'Could not delete a creative associated with the user.'));
                }
            }

            // Borrar el Usuario
            if ($user->delete() === false) {
                throw new \Exception(Yii::t('app', 'Error deleting the user.'));
            }

            // Limpiar permisos RBAC asociados
            if (isset(Yii::$app->authManager)) {
                Yii::$app->authManager->revokeAll($userId);
            }

            $transaction->commit();

            // Limpieza de archivos físicos del Avatar
            try {
                if ($avatarUrl) {
                    $this->deleteOrphanedAvatar($avatarUrl);
                }
            } catch (\Exception $e) {
                // Logueamos el error de archivo pero no fallamos la acción principal
                Yii::error('Error cleaning up user avatar: ' . $e->getMessage());
            }

            return true;

        } catch (\Exception $e) {
            // Si algo falló, deshacemos todos los cambios en la DB
            $transaction->rollBack();
            Yii::error('Error deleting user: ' . $e->getMessage());
            return false;
        }
    }
}