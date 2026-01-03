<?php

declare(strict_types=1);

namespace app\services\auth;

use app\helpers\LangHelper;
use app\helpers\StatusHelper;
use app\models\User;
use app\models\forms\auth\LoginForm;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

final class AuthLoginService
{
    public function loginAttempt(LoginForm $form): bool
    {
        $login = trim($form->login);
        $password = (string)$form->password;

        $user = User::find()
            ->where(['email' => mb_strtolower($login)])
            ->orWhere(['username' => $login])
            ->one();

        if ($user === null) {
            $form->addError('password', Yii::t('app', 'Incorrect username/email or password.'));
            return false;
        }

        // Chequeo de Bloqueo
        if ($this->isUserLocked($user)) {
            $message = Yii::t('app', 'Account temporarily locked for {n, plural, =1{# minute} other{# minutes}}.', ['n' => User::LOCK_MINUTES]);
            $form->addError('password', $message);
            return false;
        }

        // Chequeo de Status
        if ($user->status !== StatusHelper::STATUS_ACTIVE) {
            $form->addError('password', Yii::t('app', 'Your account is disabled.'));
            return false;
        }

        // Chequeo de Verificación
        if (empty($user->email_verified_at)) {
            $form->addError('password', Yii::t('app', 'Please verify your email before logging in.'));
            return false;
        }

        // Validación de Password
        if (!Yii::$app->security->validatePassword($password, (string)$user->password_hash)) {
            $this->handleLoginFailure($user);
            $form->addError('password', Yii::t('app', 'Incorrect username/email or password.'));
            return false;
        }

        // Login Exitoso
        $this->handleLoginSuccess($user, Yii::$app->request->userIP ?? '0.0.0.0');

        $duration = $form->rememberMe ? (int)(Yii::$app->params['auth.rememberDuration'] ?? ArrayHelper::getValue($_ENV, 'REMEMBER_DURATION')) : 0;

        $isLoggedIn = Yii::$app->user->login($user, $duration);

        // Configurar idioma
        if ($isLoggedIn && !empty($user->language_id)) {
            $this->setSessionLanguage($user->language_id);
        }

        return $isLoggedIn;
    }

    private function isUserLocked(User $user): bool
    {
        return !empty($user->locked_until) && (strtotime((string)$user->locked_until) > time());
    }

    private function handleLoginFailure(User $user): void
    {
        $user->failed_login_attempts = (int)$user->failed_login_attempts + 1;

        if ($user->failed_login_attempts >= User::MAX_FAILED_LOGIN_ATTEMPTS) {
            $user->locked_until = date('Y-m-d H:i:s', time() + User::LOCK_MINUTES * 60);
        }

        $user->save(false, ['failed_login_attempts', 'locked_until']);
    }

    private function handleLoginSuccess(User $user, string $ip): void
    {
        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->last_login_at = new Expression('CURRENT_TIMESTAMP');
        $user->last_login_ip = $ip;

        if (empty($user->auth_key)) {
            $user->auth_key = Yii::$app->security->generateRandomString();
        }

        $user->save(false, ['failed_login_attempts', 'locked_until', 'last_login_at', 'last_login_ip', 'auth_key']);
    }

    private function setSessionLanguage(int $languageId): void
    {
        $configs = LangHelper::getLanguagesConfig();
        foreach ($configs as $locale => $conf) {
            if ((int)($conf['id'] ?? 0) === $languageId) {
                Yii::$app->session->set('_lang', $locale);
                break;
            }
        }
    }
}