<?php

namespace app\models;

use app\helpers\StatusHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use Yii;

/**
 * Brand AR model ({{%brand}})
 * - id PK
 * - hash char(16) UNIQUE
 * - name varchar(255) UNIQUE
 * - url_name varchar(255) UNIQUE
 * - status enum
 * - created_at, updated_at
 */
final class Brand extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%brand}}';
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
            [['hash', 'name', 'url_name'], 'required'],
            [['hash', 'name', 'url_name'], 'trim'],

            [['hash', 'name', 'url_name'], 'unique'],
            ['hash', 'string', 'min' => 16, 'max' => 16],
            [['name', 'url_name'], 'string', 'max' => 255],

            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/'],
            ['url_name', 'match', 'pattern' => '/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/'],

            ['status', 'in', 'range' => [
                StatusHelper::STATUS_ACTIVE,
                StatusHelper::STATUS_ARCHIVED,
                StatusHelper::STATUS_PENDING,
            ]],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function beforeValidate(): bool
    {
        if ($this->isNewRecord && empty($this->hash)) {
            $this->hash = Yii::$app->security->generateRandomString(16);
        }
        return parent::beforeValidate();
    }

    // Relaciones
/*    public function getCreatives()
    {
        return $this->hasMany(Creative::class, ['brand_id' => 'id']);
    }*/
}