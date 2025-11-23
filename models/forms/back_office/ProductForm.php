<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use Yii;
use yii\base\Model;

final class ProductForm extends Model
{
    public const FORM_NAME = 'product-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public ?int $id = null;
    public ?string $name = null;
    public ?string $url_slug = null;
    public ?string $status = null;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['name', 'status', 'url_slug'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'name', 'status', 'url_slug'];
        $scenarios[self::SCENARIO_DELETE] = ['id'];

        return $scenarios;
    }

    public function rules(): array {
        return [
            [['name', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'name', 'status'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],

            [['name', 'url_slug', 'status'], 'trim'],

            [['name', 'url_slug'], 'string', 'max' => 255],

            [
                'url_slug',
                'match',
                'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'skipOnEmpty' => true,
                'message' => Yii::t('app', 'Use lowercase letters, numbers and dashes only.'),
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

    public function attributeLabels(): array {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'url_slug' => Yii::t('app', 'URL Slug'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}