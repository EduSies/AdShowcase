<?php

namespace app\models;

use app\helpers\StatusHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%creative}}".
 *
 * @property int $id
 * @property string $hash
 * @property int $asset_file_id
 * @property string $url_thumbnail
 * @property string $title
 * @property int $brand_id
 * @property int $agency_id
 * @property int $device_id
 * @property int $country_id
 * @property int $format_id
 * @property int $sales_type_id
 * @property int $product_id
 * @property int $language_id
 * @property string|null $click_url
 * @property string $workflow_status
 * @property string $status
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Agency $agency
 * @property AssetFile $assetFile
 * @property Brand $brand
 * @property Country $country
 * @property Device $device
 * @property Format $format
 * @property Product $product
 * @property SalesType $salesType
 * @property User $user
 */

class Creative extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%creative}}';
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

    public function rules()
    {
        return [
            // Campos Requeridos
            [['hash', 'asset_file_id', 'url_thumbnail', 'title', 'brand_id', 'agency_id', 'device_id', 'country_id', 'format_id', 'sales_type_id', 'product_id', 'language_id', 'user_id', 'click_url'], 'required'],

            [['hash'], 'string', 'min' => 16, 'max' => 16],
            [['hash'], 'unique'],

            // Enteros
            [['asset_file_id', 'brand_id', 'agency_id', 'device_id', 'country_id', 'format_id', 'sales_type_id', 'product_id', 'user_id'], 'integer'],

            // Strings y Tamaños
            [['title'], 'string', 'max' => 255],
            [['url_thumbnail', 'click_url'], 'string', 'max' => 500],
            [['click_url'], 'url', 'defaultScheme' => 'https'],

            // Language
            [['language_id'], 'integer'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => LanguageLocale::class, 'targetAttribute' => ['language_id' => 'id']],

            // Enums (Validación de rango)
            [['workflow_status'], 'in', 'range' => StatusHelper::getWorkflowStatusRange()],
            [['status'], 'in', 'range' => StatusHelper::getStatusRange(3)],

            // Valores por defecto (si fallan los defaults de DB)
            ['workflow_status', 'default', 'value' => StatusHelper::WORKFLOW_DRAFT],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],
        ];
    }

    // Relaciones
    public function getAgency()
    {
        return $this->hasOne(Agency::class, ['id' => 'agency_id']);
    }

    public function getAssetFile()
    {
        return $this->hasOne(AssetFile::class, ['id' => 'asset_file_id']);
    }

    public function getBrand()
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    public function getDevice()
    {
        return $this->hasOne(Device::class, ['id' => 'device_id']);
    }

    public function getFormat()
    {
        return $this->hasOne(Format::class, ['id' => 'format_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getSalesType()
    {
        return $this->hasOne(SalesType::class, ['id' => 'sales_type_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getLanguage()
    {
        return $this->hasOne(LanguageLocale::class, ['id' => 'language_id']);
    }
}