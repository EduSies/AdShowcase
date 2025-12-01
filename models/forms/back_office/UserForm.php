<?php

declare(strict_types=1);

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * UserForm
 *
 * Form model for creating/updating User records from BackOffice.
 * - Contains plain fields and validation rules.
 * - Does NOT persist data (that is handled by services).
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
    public string $status = StatusHelper::STATUS_ACTIVE;
    public ?int $language_id = null;
    public ?string $avatar_url = null;

    // Plain password fields (not stored directly in DB)
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
            // password fields are optional on update
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
            [['email', 'username', 'name', 'surname', 'avatar_url'], 'string', 'max' => 255],
            ['type', 'string', 'max' => 32],
            ['language_id', 'integer'],

            // Status validation
            [
                'status',
                'in',
                'range' => [
                    StatusHelper::STATUS_ACTIVE,
                    StatusHelper::STATUS_ARCHIVED,
                    StatusHelper::STATUS_PENDING,
                    StatusHelper::STATUS_BANNED,
                    StatusHelper::STATUS_INACTIVE,
                ],
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

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

            // Password: required only on create
            [['password', 'password_repeat'], 'required', 'on' => self::SCENARIO_CREATE],
            [['password', 'password_repeat'], 'string', 'min' => 8],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],

            // Unique email (ignoring current record on update)
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

            // Unique username (ignoring current record on update)
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
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Surname'),
            'status' => Yii::t('app', 'Status'),
            'language_id' => Yii::t('app', 'Language'),
            'avatar_url' => Yii::t('app', 'Avatar'),
            'password' => Yii::t('app', 'Password'),
            'password_repeat' => Yii::t('app', 'Repeat password'),
        ];
    }
}