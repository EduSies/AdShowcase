<?php

namespace app\models;

use app\helpers\StatusHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use Yii;

/**
 * Agency AR model ({{%agency}})
 * - id PK
 * - hash char(16) UNIQUE
 * - name varchar(255) UNIQUE
 * - status enum
 * - country_id int -> Country(id)
 * - created_at, updated_at
 */
final class Agency extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%agency}}';
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
            [['hash', 'name', 'status', 'country_id'], 'required'],
            [['hash', 'name', 'status'], 'trim'],

            [['hash', 'name'], 'unique'],
            ['hash', 'string', 'min' => 16, 'max' => 16],
            ['name', 'string', 'max' => 255],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/'],

            ['country_id', 'integer'],
            [
                'country_id',
                'exist',
                'skipOnError' => true,
                'targetClass' => Country::class,
                'targetAttribute' => ['country_id' => 'id'],
            ],

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
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /*    public function getCreatives()
        {
            return $this->hasMany(Creative::class, ['agency_id' => 'id']);
        }*/
}