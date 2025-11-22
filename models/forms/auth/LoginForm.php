<?php

namespace app\models\forms\auth;

use app\models\BaseModel;
use app\models\User;
use app\services\auth\AuthLoginService;
use Yii;

/**
 * Modelo de usuario que mapea a {{%user}} (con tablePrefix => ADSHOWCASE_user).
 *
 * - Implementa IdentityInterface para sesiones.
 * - Valida contraseñas con password_hash.
 * - Registra telemetría (last_login_at/ip) y bloqueo temporal por intentos fallidos.
 */
class LoginForm extends BaseModel
{
    public const FORM_MANE = 'login-form';

    public string $login = '';
    public string $password = '';
    public bool $rememberMe = true;

    public function rules(): array
    {
        return [
            [['login', 'password'], 'required'],
            ['login', 'trim'],
            ['login', 'string', 'max' => 255],
            ['password', 'string', 'min' => 6],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'login' => Yii::t('app', 'Email or username'),
            'password' => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remember'),
        ];
    }

    public function formName(): string
    {
        return self::FORM_MANE;
    }

    public function login(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $service = new AuthLoginService();
        return $service->loginAttempt($this);
    }
}