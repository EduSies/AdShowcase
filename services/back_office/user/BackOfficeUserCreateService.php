<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;
use app\models\forms\back_office\UserForm;
use Yii;

final class BackOfficeUserCreateService
{
    /**
     * Create entity from form (SCENARIO_CREATE). Returns model or null on error.
     */
    public function create(UserForm $form): ?User
    {
        $user = new User();

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

        $user->generateAuthKey();

        if (empty($user->hash)) {
            $user->hash = Yii::$app->security->generateRandomString(16);
        }

        if ($form->password !== '') {
            $user->setPassword($form->password);
        }

        if (!$user->save()) {
            $form->addErrors($user->getErrors());
            return null;
        }

        if (null !== ($rbacError = $this->syncRbacRole($user, $form->type))) {
            $form->addError('type', $rbacError);
            return null;
        }

        return $user;
    }

    /**
     * Assign RBAC role matching $type to given User.
     *
     * @param User   $user
     * @param string $type Role name (same as user type)
     *
     * @return string|null Error message on failure, or null on success.
     */
    private function syncRbacRole(User $user, string $type): ?string
    {
        if ($type === '') return null;

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($type);

        if ($role === null) {
            $message = Yii::t('app', 'RBAC role "{role}" not found while creating user #{id}', [
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