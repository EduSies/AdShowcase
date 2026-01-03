<?php

declare(strict_types=1);

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use app\models\User;
use app\validators\PasswordStrengthValidator;
use Yii;
use yii\base\Model;

/**
 * UserForm
 *
 * Form model for creating/updating User records from BackOffice.
 */
class UserForm extends Model
{
    public const FORM_NAME = 'user-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public ?int $id = null;
    public ?string $hash = null;
    public ?string $email = null;
    public ?string $username = null;
    public ?string $type = null;
    public ?string $name = null;
    public ?string $surname = null;
    public string $status = StatusHelper::STATUS_PENDING;
    public ?int $language_id = null;
    public ?string $avatar_url = null;

    public string $password = '';
    public string $password_repeat = '';

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = [
            'email', 'username', 'type', 'name', 'surname', 'status',
            'language_id', 'avatar_url',
            'password', 'password_repeat',
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'id', 'hash', 'email', 'username', 'type', 'name', 'surname', 'status',
            'language_id', 'avatar_url',
            'password', 'password_repeat',
        ];
        $scenarios[self::SCENARIO_DELETE] = ['hash'];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            // Required fields by scenario
            [
                ['email', 'username', 'type', 'name', 'surname', 'status'],
                'required',
                'on' => self::SCENARIO_CREATE,
            ],
            [
                ['email', 'username', 'type', 'name', 'surname', 'status'],
                'required',
                'on' => self::SCENARIO_UPDATE,
            ],
            [
                ['hash'],
                'required',
                'on' => self::SCENARIO_DELETE,
            ],

            // ID only used internally on update/delete
            [['id'], 'integer', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE]],

            // Basic filters
            [['email', 'username', 'name', 'surname', 'avatar_url'], 'trim'],

            // Types & lengths
            ['email', 'email'],
            [['email', 'name', 'surname'], 'string', 'max' => 255],

            ['type', 'string', 'max' => 32],
            ['language_id', 'integer'],

            // Avatar URL
            ['avatar_url', 'string'],
            ['avatar_url', 'validateAvatarSize'],

            ['username', 'string', 'max' => 10],
            [
                'username',
                'match',
                // Regex: Inicio (^), solo a-z, A-Z, 0-9, ., -, _, Fin ($)
                'pattern' => '/^[a-zA-Z0-9._-]+$/',
                'message' => Yii::t('app', 'Username can contain only letters, numbers, dots, hyphens, and underscores.')
            ],

            // Status validation
            [
                'status',
                'in',
                'range' => StatusHelper::getStatusRange(),
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => $this->status],

            ['hash', 'string', 'min' => 16, 'max' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => Yii::t('app', 'Invalid hash format.')],
            [
                'hash', 'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'hash',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } },
                'when' => fn() => !empty($this->hash),
                'skipOnEmpty' => true,
            ],

            [['password', 'password_repeat'], 'required', 'on' => self::SCENARIO_CREATE],

            // Validador de fortaleza
            ['password', PasswordStrengthValidator::class],

            ['password_repeat', 'compare',
                'compareAttribute' => 'password',
                'message' => Yii::t('app', 'Passwords do not match.')
            ],

            // Unique email
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                },
                'message' => Yii::t('app', 'This email is already in use.'),
            ],

            // Unique username
            [
                'username',
                'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'username',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                },
                'message' => Yii::t('app', 'This username is already in use.'),
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'User name'),
            'type' => Yii::t('app', 'User type'),
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Last name'),
            'status' => Yii::t('app', 'Status'),
            'language_id' => Yii::t('app', 'Default Language'),
            'avatar_url' => Yii::t('app', 'Avatar'),
            'password' => Yii::t('app', 'Password'),
            'password_repeat' => Yii::t('app', 'Repeat password'),
        ];
    }

    /**
     * Valida que el string Base64 del avatar no exceda el tamaño límite (2MB).
     */
    public function validateAvatarSize($attribute, $params)
    {
        $value = $this->$attribute;

        // Si está vacío, es corto (ruta existente) o no es base64, ignoramos.
        if (empty($value) || strlen($value) < 255 || !str_starts_with($value, 'data:image')) {
            return;
        }

        // Estimación del tamaño en Bytes: (Carácteres * 3) / 4
        // Restamos cabecera para mayor precisión si se desea, pero la estimación bruta es segura.
        $sizeInBytes = (int) (strlen($value) * (3/4));

        $limitBytes = 2 * 1024 * 1024; // 2MB

        if ($sizeInBytes > $limitBytes) {
            $this->addError($attribute, Yii::t('app', 'Avatar image cannot exceed 2MB.'));
        }
    }
}