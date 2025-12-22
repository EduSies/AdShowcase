<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;
use app\models\forms\back_office\UserForm;
use Yii;

final class BackOfficeUserUpdateService
{
    use UserAssetTrait;

    public function __construct()
    {
        $this->initUploadPath();
    }

    /**
     * Update existing user from form (SCENARIO_UPDATE).
     */
    public function update(string $hash, UserForm $form): bool
    {
        $this->tempFiles = []; // Reiniciar temporales

        $user = User::findOne(['hash' => $hash]);

        if (!$user) {
            return false;
        }

        // Guardar URL antigua para limpieza posterior (fuera de la transacción)
        $oldAvatarUrl = $user->avatar_url;

        // Validar antes de iniciar transacción
        if (!$form->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Procesar nuevo Avatar (si hay cambio, se crea fichero físico)
            $processedAvatar = $this->processAvatar($form->avatar_url);

            if ($processedAvatar) {
                $user->avatar_url = $processedAvatar;
            }

            $user->setAttributes([
                'email' => mb_strtolower($form->email),
                'username' => $form->username,
                'type' => $form->type,
                'name' => $form->name,
                'surname' => $form->surname,
                'status' => $form->status,
                'language_id' => $form->language_id,
            ]);

            if ($form->password !== '') {
                $user->setPassword($form->password);
            }

            // Guardar cambios
            if (!$user->save()) {
                $form->addErrors($user->getErrors());
                throw new \Exception(Yii::t('app', 'Error updating user.'));
            }

            // Sync RBAC
            if (null !== ($rbacError = $this->syncRbacRole($user, $form->type))) {
                $form->addError('type', $rbacError);
                throw new \Exception($rbacError);
            }

            $transaction->commit();

            // Éxito: vaciamos tempFiles para no borrar el nuevo avatar
            $this->tempFiles = [];

            // --- LIMPIEZA DE ARCHIVOS ANTIGUOS (POST-COMMIT) ---
            try {
                // Si el avatar cambió y el antiguo no es null, intentar borrarlo
                if ($oldAvatarUrl && $oldAvatarUrl !== $user->avatar_url) {
                    $this->deleteOrphanedAvatar($oldAvatarUrl);
                }
            } catch (\Exception $e) {
                // Si falla la limpieza, solo logueamos (no fallamos la acción principal)
                Yii::error('Error cleaning up orphaned avatar: ' . $e->getMessage());
            }

            return true;

        } catch (\Exception $e) {
            // Fallo: Rollback de DB y de Archivos nuevos
            $transaction->rollBack();
            $this->rollbackFiles(); // Borra el avatar nuevo que se acababa de subir

            Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed: ') . $e->getMessage());

            return false;
        }
    }
}