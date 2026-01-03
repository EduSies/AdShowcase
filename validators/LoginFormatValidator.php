<?php

namespace app\validators;

use Yii;
use yii\validators\EmailValidator;
use yii\validators\Validator;

class LoginFormatValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        // Detectamos si contiene arroba
        if (str_contains($value, '@')) {
            $emailValidator = new EmailValidator();
            if (!$emailValidator->validate($value)) {
                $this->addError($model, $attribute, Yii::t('app', 'This is not a valid email address.'));
            }
            return;
        }

        // Longitud máxima de 10
        if (mb_strlen($value) > 10) {
            $this->addError($model, $attribute, Yii::t('app', 'Username must be 10 characters or less.'));
            return;
        }

        // Caracteres permitidos
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $value)) {
            $this->addError($model, $attribute, Yii::t('app', 'Username can only contain letters, numbers, dots, hyphens, and underscores.'));
        }
    }
}