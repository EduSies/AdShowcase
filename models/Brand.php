<?php

namespace app\models;

use app\helpers\StatusHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
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
 * - created_at (datetime)
 * - updated_at (datetime)
 */
class Brand extends ActiveRecord
{
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

            // Hash pattern
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/'],

            // Slug-like url_name
            ['url_name', 'match', 'pattern' => '/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/'],

            // Status enum
            ['status', 'in', 'range' => [
                StatusHelper::STATUS_ACTIVE,
                StatusHelper::STATUS_ARCHIVED,
                StatusHelper::STATUS_PENDING,
            ]],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            // Safe dates (managed by DB/TimestampBehavior)
            [['created_at', 'updated_at'], 'safe'],
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
}