<?php

namespace app\models;

use app\helpers\StatusHelper;
use yii\db\ActiveRecord;

/**
 * Device AR model ({{%device}})
 * - id PK
 * - name varchar(100) UNIQUE
 * - status enum
 */
class Device extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%device}}';
    }

    public function rules(): array
    {
        return [
            ['hash', 'unique'],
            ['hash', 'string', 'min' => 16, 'max' => 16],

            [['name', 'status'], 'required'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 100],
            ['name', 'unique'],

            ['status', 'in', 'range' => StatusHelper::getStatusRange(3)],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],
        ];
    }

    // Relaciones
    public function getCreatives()
    {
        return $this->hasMany(Creative::class, ['device_id' => 'id']);
    }
}