<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Brand AR model (maps to ADSHOWCASE_brand via tablePrefix).
 *
 * Columns:
 * - id (int PK)
 * - hash (char(16) UNIQUE, not null)
 * - name (varchar(255) UNIQUE, not null)
 * - url_name (varchar(255) UNIQUE, not null)
 * - status (enum: active|archived|pending, default active)
 * - alcohol (tinyint(1) bool, default 0)
 * - bet (tinyint(1) bool, default 0)
 * - created_at (datetime)
 * - updated_at (datetime)
 */
class Brand extends ActiveRecord
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ARCHIVED = 'archived';
    public const STATUS_PENDING = 'pending';

    public static function tableName(): string
    {
        return '{{%brand}}';
    }

    /**
     * Ensure created_at / updated_at are filled when saving from PHP as well.
     * DB already has defaults; this keeps consistency when saving via AR.
     */
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

    /**
     * Validation rules aligned with DB constraints.
     */
    public function rules(): array
    {
        return [
            // Required
            [['hash', 'name', 'url_name'], 'required'],

            // Unique
            [['hash', 'name', 'url_name'], 'unique'],

            // Length / exact char(16) for hash
            ['hash', 'string', 'min' => 16, 'max' => 16],
            [['name', 'url_name'], 'string', 'max' => 255],

            // Optional: keep hash alphanumeric (comment out if not desired)
            // ['hash', 'match', 'pattern' => '/^[A-Za-z0-9]+$/'],

            // Slug-like url_name (optional but recommended)
            ['url_name', 'match', 'pattern' => '/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/'],

            // Status enum
            ['status', 'in', 'range' => [
                self::STATUS_ACTIVE,
                self::STATUS_ARCHIVED,
                self::STATUS_PENDING,
            ]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],

            // Booleans stored as tinyint(1)
            [['alcohol', 'bet'], 'boolean'],

            // Safe dates (managed by DB/TimestampBehavior)
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Human-friendly labels.
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'hash' => 'Hash',
            'name' => 'Name',
            'url_name' => 'URL Slug',
            'status' => 'Status',
            'alcohol' => 'Alcohol',
            'bet' => 'Bet',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Helper for forms/dropdowns.
     */
    public static function statusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_ARCHIVED => 'archived',
            self::STATUS_PENDING => 'pending',
        ];
    }

    /**
     * Example relation (if you have a Creative AR pointing to brand_id).
     * Uncomment when Creative model exists.
     */
    // public function getCreatives()
    // {
    //     return $this->hasMany(Creative::class, ['brand_id' => 'id']);
    // }

    /**
     * Normalize fields before validation/save.
     */
    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }
        // Trim and normalize slug to lowercase
        if ($this->url_name !== null) {
            $this->url_name = trim(strtolower($this->url_name));
        }
        // Trim name
        if ($this->name !== null) {
            $this->name = trim($this->name);
        }
        return true;
    }
}