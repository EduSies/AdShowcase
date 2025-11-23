<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use app\models\Agency;
use app\models\Country;
use Yii;
use yii\base\Model;

final class AgencyForm extends Model
{
    public const FORM_NAME = 'agency-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public ?int $id = null;
    public ?string $hash = null;
    public ?string $name = null;
    public ?string $status = null;
    public ?string $country_id = null;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['hash', 'name', 'status', 'country_id', 'created_at', 'updated_at'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'hash', 'name', 'status', 'country_id', 'created_at', 'updated_at'];
        $scenarios[self::SCENARIO_DELETE] = ['id'];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['name', 'status', 'country_id'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'name', 'status', 'country_id'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],
            [['id'], 'integer'],

            [['hash', 'name', 'status', 'country_id'], 'trim'],
            ['country_id', 'integer'],
            [
                'country_id',
                'exist',
                'skipOnError' => true,
                'targetClass' => Country::class,
                'targetAttribute' => ['country_id' => 'id'],
            ],

            ['hash', 'string', 'min' => 16, 'max' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => Yii::t('app', 'Invalid hash format.')],
            [
                'hash',
                'unique',
                'targetClass' => Agency::class,
                'targetAttribute' => 'hash',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                },
                'skipOnEmpty' => true,
            ],

            ['name', 'string', 'max' => 255],
            [
                'name',
                'unique',
                'targetClass' => Agency::class,
                'targetAttribute' => 'name',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                },
            ],

            ['status', 'in',
                'range' => [
                    StatusHelper::STATUS_ACTIVE,
                    StatusHelper::STATUS_ARCHIVED,
                    StatusHelper::STATUS_PENDING,
                ],
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hash' => Yii::t('app', 'Hash'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'country_id' => Yii::t('app', 'Country'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeValidate(): bool
    {
        if ($this->scenario === self::SCENARIO_CREATE && empty($this->hash)) {
            $this->hash = Yii::$app->security->generateRandomString(16);
        }
        return parent::beforeValidate();
    }
}