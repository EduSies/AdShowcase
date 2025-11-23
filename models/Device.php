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
final class Device extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%device}}';
    }

    public function rules(): array
    {
        return [
            [['name', 'status'], 'required'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 100],
            ['name', 'unique'],

            ['status', 'in', 'range' => [
                StatusHelper::STATUS_ACTIVE,
                StatusHelper::STATUS_ARCHIVED,
                StatusHelper::STATUS_PENDING,
            ]],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],
        ];
    }

    // Relaciones
/*    public function getCreatives()
    {
        return $this->hasMany(Creative::class, ['device_id' => 'id']);
    }*/
}