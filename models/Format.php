<?php

namespace app\models;

use app\helpers\StatusHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Format AR model ({{%format}})
 * - id PK
 * - name varchar(150)
 * - format varchar(100)
 * - family varchar(100)
 * - experience varchar(100)
 * - subtype varchar(100) null
 * - status enum
 * - url_slug varchar(255) UNIQUE
 * - created_at, updated_at
 */
final class Format extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%format}}';
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

            [['name','format','family','experience','status','url_slug'], 'required'],
            ['name', 'string', 'max' => 150],
            [['format','family','experience','subtype'], 'string', 'max' => 100],
            ['url_slug', 'string', 'max' => 255],
            [['name','format','family','experience','subtype','url_slug'], 'trim'],

            ['url_slug', 'unique'],
            ['url_slug', 'match', 'pattern' => '/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/'],

            ['status', 'in', 'range' => [
                StatusHelper::STATUS_ACTIVE,
                StatusHelper::STATUS_ARCHIVED,
                StatusHelper::STATUS_PENDING,
            ]],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    // Relaciones
/*    public function getCreatives()
    {
        return $this->hasMany(Creative::class, ['format_id' => 'id']);
    }*/
}