<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use app\models\Brand;
use Yii;
use yii\base\Model;

final class BrandForm extends Model
{
    public const FORM_NAME = 'brand-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    /** For updates we keep the id to exclude the current row from unique checks */
    public ?int $id = null;

    /** DB is CHAR(16) NOT NULL UNIQUE */
    public ?string $hash = null;

    /** Required, UNIQUE */
    public ?string $name = null;

    /** Slug (lowercase + dashes), UNIQUE */
    public ?string $url_name = null;

    /** enum: active|archived|pending */
    public ?string $status = null;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['name', 'url_name', 'status', 'hash'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'name', 'url_name', 'status', 'hash'];
        $scenarios[self::SCENARIO_DELETE] = ['id'];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['name', 'url_name', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'name', 'url_name', 'status'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],

            [['id'], 'integer', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE]],
            [['name', 'url_name'], 'string', 'max' => 255],

            [['name', 'url_name'], 'trim'],
            ['url_name', 'filter', 'filter' => fn($v) => mb_strtolower(trim((string)$v))],

            ['hash', 'string', 'length' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => 'Hash must be 16 chars: letters, numbers, "-" or "_"'],

            ['url_name', 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'message' => 'Use lowercase letters, numbers and dashes only.'],

            ['status', 'in',
                'range' => [
                    StatusHelper::STATUS_ACTIVE,
                    StatusHelper::STATUS_ARCHIVED,
                    StatusHelper::STATUS_PENDING,
                ],
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            [
                'name', 'unique',
                'targetClass' => Brand::class,
                'targetAttribute' => 'name',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } }
            ],
            [
                'url_name', 'unique',
                'targetClass' => Brand::class,
                'targetAttribute' => 'url_name',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } }
            ],
            [
                'hash', 'unique',
                'targetClass' => Brand::class,
                'targetAttribute' => 'hash',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } },
                'when' => fn() => !empty($this->hash),
                'skipOnEmpty' => true,
            ],

            [
                'id', 'exist',
                'targetClass' => Brand::class,
                'targetAttribute' => 'id',
                'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE],
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hash' => Yii::t('app', 'Hash'),
            'name' => Yii::t('app', 'Name'),
            'url_name' => Yii::t('app', 'URL Slug'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        // Normaliza nombre
        if ($this->name !== null) {
            $this->name = trim($this->name);
        }

        // Slug automático si falta
        if (empty($this->url_name) && !empty($this->name)) {
            $this->url_name = \yii\helpers\Inflector::slug(mb_strtolower($this->name));
        }

        // Hash automático si falta (16 chars URL-safe)
        if (empty($this->hash)) {
            $this->hash = \Yii::$app->security->generateRandomString(16);
        }

        return true;
    }
}