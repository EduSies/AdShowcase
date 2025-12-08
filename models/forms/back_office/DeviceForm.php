<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use app\models\Device;
use Yii;
use yii\base\Model;

final class DeviceForm extends Model
{
    public const FORM_NAME = 'device-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public ?int $id = null;
    public ?string $hash = null;
    public ?string $name = null;
    public string $status = StatusHelper::STATUS_ACTIVE;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    /** Which attributes are active per scenario */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['name', 'status'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'hash', 'name', 'status'];
        $scenarios[self::SCENARIO_DELETE] = ['hash'];

        return $scenarios;
    }

    /** Validation rules */
    public function rules(): array
    {
        return [
            [['name'], 'trim'],

            [['name', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'name', 'status'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],

            [['id'], 'integer', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE]],
            [['name'], 'string', 'max' => 100],

            ['status', 'in',
                'range' => StatusHelper::getRange(3),
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => $this->status],

            ['hash', 'string', 'min' => 16, 'max' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => Yii::t('app', 'Invalid hash format.')],
            [
                'hash', 'unique',
                'targetClass' => Device::class,
                'targetAttribute' => 'hash',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } },
                'when' => fn() => !empty($this->hash),
                'skipOnEmpty' => true,
            ],

            [
                'name',
                function (string $attribute): void {
                    if ($this->name === null || $this->name === '') {
                        return;
                    }
                    $query = Device::find()->where(['name' => $this->name]);
                    if ($this->scenario === self::SCENARIO_UPDATE && $this->id) {
                        $query->andWhere(['<>', 'id', (int)$this->id]);
                    }
                    if ($query->exists()) {
                        $this->addError($attribute, Yii::t('app', 'This name is already taken.'));
                    }
                },
                'skipOnEmpty' => true,
                'skipOnError' => true,
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}