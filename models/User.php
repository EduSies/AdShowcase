<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * Modelo de usuario que mapea a {{%user}} (con tablePrefix => ADSHOWCASE_user).
 *
 * - Implementa IdentityInterface para sesiones.
 * - Valida contraseñas con password_hash.
 * - Registra telemetría (last_login_at/ip) y bloqueo temporal por intentos fallidos.
 */
class User extends ActiveRecord implements IdentityInterface
{
    /** Intentos máximos antes de bloquear la cuenta temporalmente. */
    public const MAX_FAILED_LOGIN_ATTEMPTS = 5;
    /** Minutos de bloqueo tras superar el máximo de intentos. */
    public const LOCK_MINUTES = 5;
    public const STATUS_ACTIVE = 'active';

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

    /** Tabla física: con tablePrefix configurado será `ADSHOWCASE_user`. */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /** Reglas mínimas (tu CRUD puede añadir más). */
    public function rules(): array
    {
        return [
            [['email', 'username', 'name', 'surname', 'status', 'type'], 'required'],
            ['email', 'email'],

            [['language_id', 'failed_login_attempts'], 'integer'],
            [['email_verified_at', 'locked_until', 'last_login_at', 'created_at', 'updated_at'], 'safe'],

            [['hash'], 'string', 'max' => 10],
            [['auth_key'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 45],
            [['default_profile', 'status', 'type', 'name', 'surname', 'avatar_url', 'password_hash', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],

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
            'language_id' => Yii::t('app', 'Language'),
            'default_profile' => Yii::t('app', 'Default profile'),
            'avatar_url' => Yii::t('app', 'Avatar'),
            'password_hash' => Yii::t('app', 'Password'),
            //'auth_key' => Yii::t('app', 'Auth Key'),
            //'password_reset_token' => Yii::t('app', 'Reset token'),
            //'verification_token' => Yii::t('app', 'Verification token'),
            //'email_verified_at' => Yii::t('app', 'Email verified in'),
            //'failed_login_attempts' => Yii::t('app', 'Failed attempts'),
            //'locked_until' => Yii::t('app', 'Locked until'),
            //'last_login_at' => Yii::t('app', 'Last access'),
            //'last_login_ip' => Yii::t('app', 'IP address last access'),
            //'created_at' => Yii::t('app', 'Created'),
            //'updated_at' => Yii::t('app', 'Updated'),
        ];
    }

    /** @inheritdoc */
    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne($id);
    }

    /**
     * No usamos access tokens en esta tabla.
     */
    public static function findIdentityByAccessToken($token, $type = null): IdentityInterface
    {
        throw new NotSupportedException(Yii::t('app', 'findIdentityByAccessToken() no está implementado.'));
    }

    public static function findByLogin(string $login): ?self
    {
        $login = trim($login);

        $user = static::find()
            ->where(['email' => mb_strtolower($login)])
            ->orWhere(['username' => $login])
            ->one();

        return $user ?? null;
    }

    /** Política de “usuario habilitado”: ajusta a tu gusto. */
    public function isActive(): bool
    {
        $statusOk = ($this->status === self::STATUS_ACTIVE);
        $emailOk = empty($this->verification_token);
        $notLocked = !$this->isLocked();

        return $statusOk && $emailOk && $notLocked;
    }

    public function hasActiveStatus(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isEmailVerified(): bool
    {
        return !empty($this->email_verified_at);
    }

    /**
     * Permite login con username **o** email.
     */
    public static function findByUsername(string $username): ?self
    {
        return static::find()
            ->where(['username' => $username])
            ->orWhere(['email' => $username])
            ->one();
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->id;
    }

    /** @inheritdoc */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /** @inheritdoc */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Valida la contraseña en base a password_hash.
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, (string)$this->password_hash);
    }

    /**
     * Genera y guarda el hash seguro de contraseña.
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /** Genera auth_key aleatoria. */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /** Tokens de reseteo (helpers opcionales). */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /** ¿Cuenta bloqueada temporalmente? */
    public function isLocked(): bool
    {
        return !empty($this->locked_until) && (strtotime((string)$this->locked_until) > time());
    }

    /** Marca fallo de login y bloquea si procede. */
    public function markLoginFailure(): void
    {
        $this->failed_login_attempts = (int)$this->failed_login_attempts + 1;

        if ($this->failed_login_attempts >= self::MAX_FAILED_LOGIN_ATTEMPTS) {
            $this->locked_until = date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60);
        }

        // Guardamos sólo columnas modificadas (sin validaciones).
        $this->save(false, ['failed_login_attempts', 'locked_until']);
    }

    /** Marca login correcto: resetea contadores y registra telemetría. */
    public function markLoginSuccess(string $ip): void
    {
        $this->failed_login_attempts = 0;
        $this->locked_until = null;
        $this->last_login_at = new Expression('CURRENT_TIMESTAMP');
        $this->last_login_ip = $ip;

        if (empty($this->auth_key)) {
            $this->generateAuthKey();
        }

        $this->save(false, ['failed_login_attempts', 'locked_until', 'last_login_at', 'last_login_ip', 'auth_key']);
    }
}