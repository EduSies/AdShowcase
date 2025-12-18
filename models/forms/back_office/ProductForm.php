<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use app\models\Product;
use Yii;
use yii\base\Model;

class ProductForm extends Model
{
    public const FORM_NAME = 'product-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public ?int $id = null;
    public ?string $hash = null;
    public ?string $name = null;
    public ?string $url_slug = null;
    public string $status = StatusHelper::STATUS_ACTIVE;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['name', 'status', 'url_slug'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'hash', 'name', 'status', 'url_slug'];
        $scenarios[self::SCENARIO_DELETE] = ['hash'];

        return $scenarios;
    }

    public function rules(): array {
        return [
            [['name', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'name', 'status'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],

            [['name', 'url_slug', 'status'], 'trim'],

            [['name', 'url_slug'], 'string', 'max' => 255],

            ['hash', 'string', 'min' => 16, 'max' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => Yii::t('app', 'Invalid hash format.')],
            [
                'hash', 'unique',
                'targetClass' => Product::class,
                'targetAttribute' => 'hash',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } },
                'when' => fn() => !empty($this->hash),
                'skipOnEmpty' => true,
            ],

            [
                'url_slug',
                'match',
                'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'skipOnEmpty' => true,
                'message' => Yii::t('app', 'Use lowercase letters, numbers and dashes only.'),
            ],

            ['status', 'in',
                'range' => StatusHelper::getStatusRange(3),
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => $this->status],
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