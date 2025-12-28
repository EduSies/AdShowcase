<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $hash
 * @property int $user_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 */
class FavList extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%fav_list}}';
    }

    public function rules()
    {
        return [
            [['hash', 'user_id', 'name'], 'required'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['hash'], 'string', 'max' => 16],
            [['hash'], 'unique'],
        ];
    }

    public function getItems()
    {
        return $this->hasMany(FavListItem::class, ['list_id' => 'id']);
    }
}