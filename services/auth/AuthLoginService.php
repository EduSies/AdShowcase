<?php

declare(strict_types=1);

namespace app\services\auth;

use app\helpers\LangHelper;
use app\models\User;
use app\models\forms\auth\LoginForm;
use Yii;
use yii\helpers\ArrayHelper;

final class AuthLoginService
{
    public function loginAttempt(LoginForm $form): bool
    {
        $login = trim($form->login);
        $password = (string)$form->password;

        $user = User::findByLogin($login);
        if ($user === null) {
            $form->addError('password', Yii::t('app', 'Incorrect username/email or password.'));
            return false;
        }

        if ($user->isLocked()) {
            $message = Yii::t('app', 'Account temporarily locked for {n, plural, =1{# minute} other{# minutes}}.', ['n' => \app\models\User::LOCK_MINUTES]);
            $form->addError('password', $message);
            return false;
        }

        if (!$user->hasActiveStatus()) {
            $form->addError('password', Yii::t('app', 'Your account is disabled.'));
            return false;
        }

        if (!$user->isEmailVerified()) {
            $form->addError('password', Yii::t('app', 'Please verify your email before logging in.'));
            return false;
        }

        if (!$user->validatePassword($password)) {
            $user->markLoginFailure();
            $form->addError('password', Yii::t('app', 'Incorrect username/email or password.'));
            return false;
        }

        $user->markLoginSuccess(Yii::$app->request->userIP ?? '0.0.0.0');

        $duration = $form->rememberMe ? (int)(Yii::$app->params['auth.rememberDuration'] ?? ArrayHelper::getValue($_ENV, 'REMEMBER_DURATION')) : 0;

        $isLoggedIn = Yii::$app->user->login($user, $duration);

        // SOLO si el login fue exitoso Y el usuario tiene idioma configurado
        if ($isLoggedIn && !empty($user->language_id)) {
            $configs = LangHelper::getLanguagesConfig();

            // Buscamos qué código (ej: 'es-ES') corresponde a este ID
            foreach ($configs as $locale => $conf) {
                if ((int)($conf['id'] ?? 0) === (int)$user->language_id) {
                    Yii::$app->session->set('_lang', $locale);
                    break;
                }
            }
        }

        return $isLoggedIn;
    }
}