<?php

namespace app\models\forms\auth;

use Yii;
use yii\base\Model;
use yii\validators\EmailValidator;

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

            ['login', 'validateLoginFormat'],
            ['password', 'validatePasswordStrength'],

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

    /**
     * Validador inteligente:
     * - Si tiene '@', valida exclusivamente como Email.
     * - Si NO tiene '@', valida exclusivamente como Username.
     */
    public function validateLoginFormat($attribute)
    {
        $value = $this->$attribute;

        // CASO 1: INTENTO DE EMAIL (Detectamos si contiene arroba)
        if (str_contains($value, '@')) {
            $emailValidator = new EmailValidator();
            if (!$emailValidator->validate($value)) {
                // Es un email, pero está mal formado (ej: "pepe@gmail")
                $this->addError($attribute, Yii::t('app', 'This is not a valid email address.'));
            }
            return;
        }

        // CASO 2: INTENTO DE USERNAME (No tiene arroba)
        // Aquí aplicamos tus restricciones estrictas (Max 10, sin acentos, etc.)

        // A) Longitud máxima de 10
        if (mb_strlen($value) > 10) {
            $this->addError($attribute, Yii::t('app', 'Username must be 10 characters or less.'));
            return;
        }

        // B) Caracteres permitidos (letras, números, puntos, guiones)
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $value)) {
            $this->addError($attribute, Yii::t('app', 'Username can only contain letters, numbers, dots, hyphens, and underscores.'));
        }
    }

    /**
     * Validador personalizado de fortaleza de contraseña.
     */
    public function validatePasswordStrength($attribute)
    {
        if (!$this->hasErrors()) {
            $password = $this->$attribute;

            if (strlen($password) < 8) {
                $this->addError($attribute, Yii::t('app', 'Password must be at least 8 characters long.'));
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $this->addError($attribute, Yii::t('app', 'Password must contain at least one uppercase letter.'));
            }
            if (!preg_match('/[a-z]/', $password)) {
                $this->addError($attribute, Yii::t('app', 'Password must contain at least one lowercase letter.'));
            }
            if (!preg_match('/[0-9]/', $password)) {
                $this->addError($attribute, Yii::t('app', 'Password must contain at least one number.'));
            }
            if (!preg_match('/[\W_]/', $password)) {
                $this->addError($attribute, Yii::t('app', 'Password must contain at least one symbol.'));
            }
        }
    }
}