<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $hash
 * @property int $list_id
 * @property int $creative_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Creative $creative
 * @property FavList $list
 */
class FavListItem extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%fav_list_item}}';
    }

    public function rules()
    {
        return [
            [['hash', 'list_id', 'creative_id'], 'required'],
            [['list_id', 'creative_id'], 'integer'],
            [['hash'], 'string', 'max' => 16],
            [['hash'], 'unique'],
        ];
    }

    public function getCreative()
    {
        return $this->hasOne(Creative::class, ['id' => 'creative_id']);
    }

    public function getList()
    {
        return $this->hasOne(FavList::class, ['id' => 'list_id']);
    }
}