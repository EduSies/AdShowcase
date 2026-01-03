<?php

declare(strict_types=1);

namespace app\services\auth;

use app\helpers\StatusHelper;
use app\models\User;
use app\services\email\EmailSenderService;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Expression;
use yii\helpers\Url;

final class AuthService
{
    /** Tiempo de validez del token en segundos.
     ** 86400 segundos = 24 horas.
     **/
    private const RESET_TOKEN_TIMEOUT = 86400;

    public function setPassword(User $user, string $password): void
    {
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Busca un usuario por token de reset, validando que exista, esté activo y no haya caducado.
     * @throws InvalidArgumentException Si el token es inválido o ha caducado.
     */
    public function findByPasswordResetToken(string $token): User
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException(Yii::t('app', 'Password reset token cannot be blank.'));
        }

        $user = User::findOne([
            'password_reset_token' => $token,
            'status' => StatusHelper::STATUS_ACTIVE,
        ]);

        if (!$user) {
            throw new InvalidArgumentException(Yii::t('app', 'Wrong password reset token.'));
        }

        // Validación de caducidad (24h = 86400 segundos)
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);

        if ($timestamp + self::RESET_TOKEN_TIMEOUT < time()) {
            throw new InvalidArgumentException(Yii::t('app', 'Token expired.'));
        }

        return $user;
    }

    /**
     * Ejecuta el cambio de contraseña final y limpia el token.
     */
    public function resetUserPassword(User $user, string $password): bool
    {
        $this->setPassword($user, $password);
        $this->removePasswordResetToken($user);

        return $user->save(false);
    }

    public function generatePasswordResetToken(User $user): void
    {
        $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken(User $user): void
    {
        $user->password_reset_token = null;
    }

    public function generateVerificationToken(User $user): void
    {
        $user->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function sendVerificationEmail(User $user): bool
    {
        if (!$user->verification_token) {
            return false;
        }

        $verifyLink = Url::to(['auth/verify-email', 'token' => $user->verification_token], true);

        $htmlContent = Yii::$app->mailer->render('auth/verify-email', [
            'user' => $user,
            'url' => $verifyLink
        ]);

        $emailService = new EmailSenderService();
        return $emailService->sendHtml(
            $user->email,
            Yii::t('app', 'Welcome to AdShowcase - Verify your email'),
            $htmlContent
        );
    }

    public function verifyEmailToken(string $token): ?User
    {
        if (empty($token)) {
            throw new InvalidArgumentException(Yii::t('app', 'Token cannot be blank.'));
        }

        $user = User::findOne([
            'verification_token' => $token,
            'status' => [StatusHelper::STATUS_INACTIVE, StatusHelper::STATUS_PENDING, StatusHelper::STATUS_ACTIVE]
        ]);

        if (!$user) {
            return null;
        }

        $user->verification_token = null;
        $user->email_verified_at = new Expression('CURRENT_TIMESTAMP');
        $user->status = StatusHelper::STATUS_ACTIVE;

        $user->save(false, ['verification_token', 'email_verified_at', 'status']);

        return $user;
    }

    /**
     * Regenera el token, pone al usuario en PENDING y reenvía el email.
     */
    public function resendUserVerification(string $hash): bool
    {
        if (empty($hash)) {
            return false;
        }

        $user = User::findOne(['hash' => $hash]);

        if (!$user) {
            return false;
        }

        $this->generateVerificationToken($user);
        $user->status = StatusHelper::STATUS_PENDING;

        if ($user->save(false, ['verification_token', 'status'])) {
            return $this->sendVerificationEmail($user);
        }

        return false;
    }

    /**
     * Gestiona la solicitud de reseteo: Genera token y envía email.
     */
    public function requestPasswordReset(string $email): bool
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => StatusHelper::STATUS_ACTIVE,
            'email' => $email,
        ]);

        if (!$user) {
            return false;
        }

        // Generar Token
        if (!$this->isPasswordResetTokenValid($user->password_reset_token)) {
            $this->generatePasswordResetToken($user);
            if (!$user->save(false)) {
                return false;
            }
        }

        $resetLink = Url::to(['auth/reset-password', 'token' => $user->password_reset_token], true);

        $htmlContent = Yii::$app->mailer->render('auth/request-password-reset', [
            'user' => $user,
            'url' => $resetLink
        ]);

        // Enviar Email
        $emailService = new EmailSenderService();
        return $emailService->sendHtml(
            $user->email,
            Yii::t('app', 'Password Reset for AdShowcase'),
            $htmlContent
        );
    }

    /**
     * Comprueba si un token es válido (no está vacío y no ha caducado).
     */
    private function isPasswordResetTokenValid(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        // Extraemos el timestamp del token (formato: randomString_timestamp)
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);

        // Verificamos si sigue dentro del tiempo de validez definido en la constante
        return $timestamp + self::RESET_TOKEN_TIMEOUT >= time();
    }
}