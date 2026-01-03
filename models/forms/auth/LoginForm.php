<?php

namespace app\models\forms\auth;

use app\validators\LoginFormatValidator;
use app\validators\PasswordStrengthValidator;
use Yii;
use yii\base\Model;

/**
 * Modelo de usuario que mapea a {{%user}} (con tablePrefix => ADSHOWCASE_user).
 *
 * - Implementa IdentityInterface para sesiones.
 * - Valida contraseñas con password_hash.
 * - Registra telemetría (last_login_at/ip) y bloqueo temporal por intentos fallidos.
 */
class LoginForm extends Model
{
    public const FORM_NAME = 'login-form';

    public string $login = '';
    public string $password = '';
    public bool $rememberMe = false;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function rules(): array
    {
        return [
            [['login', 'password'], 'required'],
            ['login', 'trim'],

            ['login', 'string', 'max' => 255],

            ['login', LoginFormatValidator::class],
            ['password', PasswordStrengthValidator::class],

            ['rememberMe', 'boolean'],
            ['rememberMe', 'default', 'value' => $this->rememberMe],
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
}