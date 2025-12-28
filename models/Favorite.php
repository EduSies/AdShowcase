<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Representa la lista por defecto "Tus favoritos" (Tabla simple).
 *
 * @property int $user_id
 * @property int $creative_id
 * @property string $created_at
 *
 * @property Creative $creative
 */
class Favorite extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%favorite}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'creative_id'], 'required'],
            [['user_id', 'creative_id'], 'integer'],
            [['user_id', 'creative_id'], 'unique', 'targetAttribute' => ['user_id', 'creative_id']],
        ];
    }

    public function getCreative()
    {
        return $this->hasOne(Creative::class, ['id' => 'creative_id']);
    }
}