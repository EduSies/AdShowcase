<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\helpers\StatusHelper;
use app\models\User;
use app\models\forms\back_office\UserForm;
use app\services\auth\AuthService;
use Yii;

final class BackOfficeUserCreateService
{
    use UserAssetTrait;

    public function __construct()
    {
        $this->initUploadPath();
    }

    public function create(UserForm $form): ?User
    {
        $this->tempFiles = [];

        if (!$form->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = new User();
            $authService = new AuthService();

            $processedAvatar = $this->processAvatar($form->avatar_url);

            $user->setAttributes([
                'email' => mb_strtolower($form->email),
                'username' => $form->username,
                'type' => $form->type,
                'name' => $form->name,
                'surname' => $form->surname,
                'status' => StatusHelper::STATUS_PENDING,
                'language_id' => $form->language_id,
                'avatar_url' => $processedAvatar,
            ]);

            $authService->setPassword($user, $form->password);
            $authService->generateVerificationToken($user);

            if (empty($user->hash)) {
                $user->hash = Yii::$app->security->generateRandomString(16);
            }

            $user->auth_key = Yii::$app->security->generateRandomString();

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