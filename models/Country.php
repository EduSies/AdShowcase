<?php

namespace app\models;

use app\helpers\StatusHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Country AR model ({{%country}})
 * PK: id (int)
 * - id (int)
 * - iso (char(2))
 * - iso3 (char(3) null)
 * - name (varchar(255))
 * - continent_code (char(2) null)
 * - currency_code  (char(3) null)
 * - status enum
 * - url_slug varchar(255) null
 * - created_at, updated_at
 */
class Country extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%country}}';
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

            [['iso', 'name', 'status'], 'required'],
            [['id'], 'integer'],
            [['iso', 'continent_code'], 'string', 'length' => 2],
            [['iso3', 'currency_code'], 'string', 'length' => 3],
            [['name', 'url_slug'], 'string', 'max' => 255],
            [['iso', 'iso3', 'continent_code', 'currency_code', 'name', 'url_slug'], 'trim'],

            ['iso', 'match', 'pattern' => '/^[A-Z]{2}$/'],
            ['iso3', 'match', 'pattern' => '/^[A-Z]{3}$/', 'skipOnEmpty' => true],
            ['continent_code', 'match', 'pattern' => '/^[A-Z]{2}$/', 'skipOnEmpty' => true],
            ['currency_code', 'match', 'pattern' => '/^[A-Z]{3}$/', 'skipOnEmpty' => true],
            ['url_slug', 'match', 'pattern' => '/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/'],

            ['status', 'in', 'range' => StatusHelper::getStatusRange(3)],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function beforeValidate(): bool
    {
        $this->iso = strtoupper((string)$this->iso);

        if (!empty($this->iso3)) {
            $this->iso3 = strtoupper((string)$this->iso3);
        }
        if (!empty($this->continent_code)) {
            $this->continent_code = strtoupper((string)$this->continent_code);
        }
        if (!empty($this->currency_code)){
            $this->currency_code = strtoupper((string)$this->currency_code);
        }

        return parent::beforeValidate();
    }

    // Relaciones
    public function getAgencies()
    {
        return $this->hasMany(Agency::class, ['country_id' => 'id']);
    }

    public function getCreatives()
    {
        return $this->hasMany(Creative::class, ['country_id' => 'id']);
    }
}