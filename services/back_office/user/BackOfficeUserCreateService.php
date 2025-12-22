<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;
use app\models\forms\back_office\UserForm;
use Yii;

final class BackOfficeUserCreateService
{
    use UserAssetTrait;

    public function __construct()
    {
        $this->initUploadPath();
    }

    /**
     * Create entity from form (SCENARIO_CREATE). Returns model or null on error.
     */
    public function create(UserForm $form): ?User
    {
        // Reiniciar registro de archivos temporales del trait
        $this->tempFiles = [];

        // Validamos el form antes de abrir transacción
        if (!$form->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = new User();

            // Procesar Avatar (Base64 -> Archivo) usando el Trait
            // Si esto crea un archivo, se añade a $this->tempFiles
            $processedAvatar = $this->processAvatar($form->avatar_url);

            $user->setAttributes([
                'email' => mb_strtolower($form->email),
                'username' => $form->username,
                'type' => $form->type,
                'name' => $form->name,
                'surname' => $form->surname,
                'status' => $form->status,
                'language_id' => $form->language_id,
                'avatar_url' => $processedAvatar,
            ]);

            $user->generateAuthKey();

            if (empty($user->hash)) {
                $user->hash = Yii::$app->security->generateRandomString(16);
            }

            if ($form->password !== '') {
                $user->setPassword($form->password);
            }

            // Guardar Usuario
            if (!$user->save()) {
                $form->addErrors($user->getErrors());
                throw new \Exception(Yii::t('app', 'Error saving user.'));
            }

            // Asignar Rol (RBAC) dentro de la transacción
            if (null !== ($rbacError = $this->syncRbacRole($user, $form->type))) {
                $form->addError('type', $rbacError);
                throw new \Exception($rbacError);
            }

            // Confirmar transacción
            $transaction->commit();

            // Limpiamos el array temporal para que NO se borren los archivos (éxito)
            $this->tempFiles = [];

            return $user;

        } catch (\Exception $e) {
            // En caso de error: Rollback BD y Rollback Archivos
            $transaction->rollBack();
            $this->rollbackFiles(); // Borra la foto si se llegó a crear

            Yii::$app->session->setFlash('error', Yii::t('app', 'Creation failed: ') . $e->getMessage());

            return null;
        }
    }
}