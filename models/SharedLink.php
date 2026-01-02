<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $hash
 * @property string $token
 * @property int $creative_id
 * @property int $user_id
 * @property string|null $expires_at
 * @property int|null $max_uses
 * @property int $used_count
 * @property string|null $revoked_at
 * @property string $created_at
 * @property string|null $note
 *
 * @property Creative $creative
 * @property User $user
 * @property SharedLinkAccessLog[] $logs
 */
class SharedLink extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shared_link}}';
    }

    public function rules()
    {
        return [
            [['hash', 'token', 'creative_id', 'user_id'], 'required'],
            [['creative_id', 'user_id', 'max_uses', 'used_count'], 'integer'],
            [['expires_at', 'revoked_at', 'created_at'], 'safe'],
            [['token'], 'string', 'max' => 43],
            [['token'], 'unique'],
            [['note'], 'string', 'max' => 500],
        ];
    }

    // Relaciones
    public function getCreative()
    {
        return $this->hasOne(Creative::class, ['id' => 'creative_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getLogs()
    {
        return $this->hasMany(SharedLinkAccessLog::class, ['shared_link_id' => 'id'])
            ->orderBy(['accessed_at' => SORT_DESC]);
    }
}