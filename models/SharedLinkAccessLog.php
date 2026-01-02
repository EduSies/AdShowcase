<?php

namespace app\models;

use yii\db\ActiveRecord;

class SharedLinkAccessLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shared_link_access_log}}';
    }

    public function rules()
    {
        return [
            [['shared_link_id', 'ip', 'user_agent'], 'required'],
            [['shared_link_id'], 'integer'],
            [['accessed_at'], 'safe'],
            [['ip'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 255],
        ];
    }

    // Relaciones
    public function getSharedLink()
    {
        return $this->hasOne(SharedLink::class, ['id' => 'shared_link_id']);
    }
}