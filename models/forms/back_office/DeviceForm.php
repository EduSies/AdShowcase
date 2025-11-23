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
    public ?string $name = null;
    public ?string $status = null;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    /** Which attributes are active per scenario */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['name', 'status'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'name', 'status'];
        $scenarios[self::SCENARIO_DELETE] = ['id'];

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
                'range' => [
                    StatusHelper::STATUS_ACTIVE,
                    StatusHelper::STATUS_ARCHIVED,
                    StatusHelper::STATUS_PENDING,
                ],
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

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