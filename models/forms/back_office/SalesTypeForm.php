<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use yii\base\Model;
use app\models\SalesType;
use Yii;

final class SalesTypeForm extends Model
{
    public const FORM_NAME = 'sales-type-form';

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

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['name', 'status'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'name', 'status'];
        $scenarios[self::SCENARIO_DELETE] = ['id'];
        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['name', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'name', 'status'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],

            [['id'], 'integer', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE]],
            [['name', 'status'], 'trim'],
            ['name', 'string', 'max' => 150],

            [
                'name',
                'unique',
                'targetClass' => SalesType::class,
                'targetAttribute' => 'name',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                },
                'message' => Yii::t('app', 'This name is already taken.'),
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
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}