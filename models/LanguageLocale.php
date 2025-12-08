<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Modelo para la tabla {{%language_locale}}.
 * * @property int $id
 * @property string $language_code
 * @property string|null $region_code
 * @property string $locale_code
 * @property string $display_name_en
 * @property string $display_name_es
 * @property string $display_name_ca
 * @property int $is_default
 * @property string $status
 */
class LanguageLocale extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%language_locale}}';
    }

    public function rules(): array
    {
        return [
            [['language_code', 'locale_code', 'display_name_en', 'display_name_es', 'display_name_ca'], 'required'],
            [['is_default'], 'integer'],
            [['status'], 'string'],
            [['language_code', 'region_code'], 'string', 'max' => 2],
            [['locale_code'], 'string', 'max' => 10],
            [['display_name_en', 'display_name_es', 'display_name_ca'], 'string', 'max' => 128],
            [['locale_code'], 'unique'],
        ];
    }
}