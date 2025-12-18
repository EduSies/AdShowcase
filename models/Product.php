<?php

namespace app\models;

use app\helpers\StatusHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Product AR model ({{%product}})
 * - id PK
 * - name varchar(255)
 * - url_slug varchar(255) null
 * - status enum
 * - created_at, updated_at
 */
class Product extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%product}}';
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
            ['name', 'string', 'max' => 255],
            ['url_slug', 'string', 'max' => 255],
            [['name','url_slug'], 'trim'],

            ['url_slug', 'match', 'pattern' => '/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/'],

            ['status', 'in', 'range' => StatusHelper::getStatusRange(3)],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    // Relaciones
    public function getCreatives()
    {
        return $this->hasMany(Creative::class, ['product_id' => 'id']);
    }
}