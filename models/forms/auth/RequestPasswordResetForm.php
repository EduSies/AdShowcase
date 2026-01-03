<?php

namespace app\models\forms\auth;

use app\helpers\StatusHelper;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Formulario para solicitar el reset de contraseña.
 */
class RequestPasswordResetForm extends Model
{
    public const FORM_NAME = 'request-password-reset-form';

    public $email;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],

            // Validamos que el email exista y el usuario esté activo
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => StatusHelper::STATUS_ACTIVE],
                'message' => Yii::t('app', 'There is no user with this email address.')
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
        ];
    }
}