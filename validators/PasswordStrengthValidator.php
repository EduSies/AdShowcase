<?php

namespace app\validators;

use Yii;
use yii\validators\Validator;

class PasswordStrengthValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $password = $model->$attribute;

        if (strlen($password) < 8) {
            $this->addError($model, $attribute, Yii::t('app', 'Password must be at least 8 characters long.'));
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->addError($model, $attribute, Yii::t('app', 'Password must contain at least one uppercase letter.'));
        }
        if (!preg_match('/[a-z]/', $password)) {
            $this->addError($model, $attribute, Yii::t('app', 'Password must contain at least one lowercase letter.'));
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->addError($model, $attribute, Yii::t('app', 'Password must contain at least one number.'));
        }
        if (!preg_match('/[\W_]/', $password)) {
            $this->addError($model, $attribute, Yii::t('app', 'Password must contain at least one symbol.'));
        }
    }
}