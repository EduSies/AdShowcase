<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $hash_sha256
 * @property string $storage_path
 * @property string $mime
 * @property int|null $width
 * @property int|null $height
 * @property int|null $duration_sec
 * @property string $created_at
 */
class AssetFile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%asset_file}}';
    }

    public function rules()
    {
        return [
            [['hash_sha256', 'storage_path', 'mime'], 'required'],
            [['hash_sha256'], 'unique'],
            [['width', 'height', 'duration_sec'], 'integer'],
            [['storage_path'], 'string', 'max' => 500],
            [['mime'], 'string', 'max' => 100],
            [['hash_sha256'], 'string', 'max' => 64],
        ];
    }
}