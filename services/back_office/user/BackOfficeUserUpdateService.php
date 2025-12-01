<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;
use app\models\forms\back_office\UserForm;
use Yii;

final class BackOfficeUserUpdateService
{
    /**
     * Update existing user from form (SCENARIO_UPDATE).
     */
    public function update(string $hash, UserForm $form): bool
    {
        $user = User::findOne(['hash' => $hash]);

        if (!$user) {
            $form->addErrors($user->getErrors());
            return false;
        }

        $user->setAttributes([
            'email' => mb_strtolower($form->email),
            'username' => $form->username,
            'type' => $form->type,
            'name' => $form->name,
            'surname' => $form->surname,
            'status' => $form->status,
            'language_id' => $form->language_id,
            'avatar_url' => $form->avatar_url,
        ]);

        if ($form->password !== '') {
            $user->setPassword($form->password);
        }

        if (!$user->save()) {
            $form->addErrors($user->getErrors());
            return false;
        }

        if (null !== ($rbacError = $this->syncRbacRole($user, $form->type))) {
            $form->addError('type', $rbacError);
            return false;
        }

        return true;
    }

    /**
     * Sync RBAC role with current user type:
     * - revoke all existing roles
     * - assign the role that matches $type (if exists).
     *
     * @param User   $user
     * @param string $type
     *
     * @return string|null Error message on failure, or null on success.
     */
    private function syncRbacRole(User $user, string $type): ?string
    {
        if ($type === '') return null;

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($type);

        if ($role === null) {
            $message = Yii::t('app', 'RBAC role "{role}" not found while updating user #{id}', [
                'role' => $type,
                'id' => $user->id,
            ]);
            Yii::warning($message, __METHOD__);

            return $message;
        }

        // Por si acaso alguien ha asignado algo antes (no debería en create), eliminamos cualquier permiso existente
        $auth->revokeAll((string) $user->id);
        $auth->assign($role, (string) $user->id);

        return null;
    }
}