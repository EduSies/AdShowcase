<?php

namespace app\models;

use app\helpers\StringHelper;
use yii\base\Model;
use yii\helpers\Inflector;

class BaseModel extends Model
{
    public function getClassName(): string
    {
        $class = static::class;
        $class = explode("\\", $class);
        $class = end($class);

        return Inflector::titleize($class);
    }

    public function getName(): string|null
    {
        return StringHelper::titleize($this->name);
    }
}