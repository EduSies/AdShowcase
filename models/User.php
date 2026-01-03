<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public const MAX_FAILED_LOGIN_ATTEMPTS = 5;
    public const LOCK_MINUTES = 5;

    public static function tableName(): string
    {
        return '{{%user}}';
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if (isset($this->email) && $this->email !== null) {
            $this->email = mb_strtolower($this->email);
        }
        return true;
    }

    public function rules(): array
    {
        return [
            [['email', 'username', 'name', 'surname', 'status', 'type'], 'required'],
            ['email', 'email'],
            [['language_id', 'failed_login_attempts'], 'integer'],
            [['email_verified_at', 'locked_until', 'last_login_at', 'created_at', 'updated_at'], 'safe'],
            ['hash', 'string', 'min' => 16, 'max' => 16],
            [['auth_key'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 45],
            [['status', 'type', 'name', 'surname', 'avatar_url', 'password_hash', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],
            [['email', 'username', 'hash', 'password_reset_token', 'verification_token'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hash' => Yii::t('app', 'Hash'),
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'User name'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Surname'),
            'status' => Yii::t('app', 'Status'),
            'language_id' => Yii::t('app', 'Default Language'),
            'avatar_url' => Yii::t('app', 'Avatar'),
            'password_hash' => Yii::t('app', 'Password'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_reset_token' => Yii::t('app', 'Reset token'),
            'verification_token' => Yii::t('app', 'Verification token'),
            'email_verified_at' => Yii::t('app', 'Email verified in'),
            'failed_login_attempts' => Yii::t('app', 'Failed attempts'),
            'locked_until' => Yii::t('app', 'Locked until'),
            'last_login_at' => Yii::t('app', 'Last access'),
            'last_login_ip' => Yii::t('app', 'IP address last access'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    // IDENTITY INTERFACE (Requeridas por Yii)
    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): IdentityInterface
    {
        throw new NotSupportedException(Yii::t('app', 'findIdentityByAccessToken() no está implementado.'));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    // --- RELACIONES ---
    public function getLanguage()
    {
        return $this->hasOne(LanguageLocale::class, ['id' => 'language_id']);
    }
}