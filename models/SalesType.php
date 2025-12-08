<?php

namespace app\models;

use app\helpers\StatusHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * SalesType AR model ({{%sales_type}})
 * - id PK
 * - name varchar(150) UNIQUE
 * - status enum
 * - created_at, updated_at
 */
final class SalesType extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%sales_type}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
        ];
    }

    public function rules(): array
    {
        return [
            ['hash', 'unique'],
            ['hash', 'string', 'min' => 16, 'max' => 16],

            [['name', 'status'], 'required'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 150],
            ['name', 'unique'],

            ['status', 'in', 'range' => StatusHelper::getRange(3)],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    // Relaciones
/*    public function getCreatives()
    {
        return $this->hasMany(Creative::class, ['sales_type_id' => 'id']);
    }*/
}