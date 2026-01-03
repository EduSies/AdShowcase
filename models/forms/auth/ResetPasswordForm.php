<?php

namespace app\models\forms\auth;

use app\validators\PasswordStrengthValidator;
use Yii;
use yii\base\Model;

/**
 * Formulario para validar la nueva contraseña.
 */
class ResetPasswordForm extends Model
{
    public const FORM_NAME = 'reset-password-form';

    public $password;
    public $password_repeat;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required'],

            // Validador de coincidencia
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', 'Passwords do not match.')],

            // Validador de fortaleza
            ['password', PasswordStrengthValidator::class],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'New Password'),
            'password_repeat' => Yii::t('app', 'Repeat Password'),
        ];
    }
}