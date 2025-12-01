<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use app\models\Country;
use Yii;
use yii\base\Model;

final class CountryForm extends Model
{
    public const FORM_NAME = 'country-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public ?int $id = null;
    public ?string $hash = null;
    public ?string $iso = null;
    public ?string $iso3 = null;
    public ?string $name = null;
    public ?string $continent_code = null;
    public ?string $currency_code = null;
    public string $status = StatusHelper::STATUS_ACTIVE;
    public ?string $url_slug = null;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = [
            'iso', 'iso3', 'name', 'continent_code', 'currency_code', 'status', 'url_slug',
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'id', 'hash', 'iso', 'iso3', 'name', 'continent_code', 'currency_code', 'status', 'url_slug',
        ];

        $scenarios[self::SCENARIO_DELETE] = ['hash'];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['iso','name','status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id','iso','name','status'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],

            ['hash', 'string', 'min' => 16, 'max' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => Yii::t('app', 'Invalid hash format.')],
            [
                'hash', 'unique',
                'targetClass' => Country::class,
                'targetAttribute' => 'hash',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } },
                'when' => fn() => !empty($this->hash),
                'skipOnEmpty' => true,
            ],

            [['iso','iso3','continent_code','currency_code','name','url_slug','status'], 'trim'],
            [['iso','iso3','continent_code','currency_code'], 'filter', 'filter' => 'strtoupper'],
            [['id'], 'integer'],

            ['iso', 'match', 'pattern' => '/^[A-Z]{2}$/', 'message' => Yii::t('app','Use two uppercase letters.')],
            ['iso3', 'match', 'pattern' => '/^[A-Z]{3}$/', 'skipOnEmpty' => true, 'message' => Yii::t('app','Use three uppercase letters.')],
            ['continent_code', 'match', 'pattern' => '/^[A-Z]{2}$/', 'skipOnEmpty' => true, 'message' => Yii::t('app','Use two uppercase letters.')],
            ['currency_code', 'match', 'pattern' => '/^[A-Z]{3}$/', 'skipOnEmpty' => true, 'message' => Yii::t('app','Use three uppercase letters.')],

            [['name','url_slug'], 'string', 'max' => 255],

            ['url_slug', 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'skipOnEmpty' => true, 'message' => Yii::t('app','Use lowercase letters, numbers and dashes only.')],

            ['status', 'in',
                'range' => [
                    StatusHelper::STATUS_ACTIVE,
                    StatusHelper::STATUS_ARCHIVED,
                    StatusHelper::STATUS_PENDING,
                ],
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            ['iso', 'unique', 'targetClass' => Country::class, 'targetAttribute' => 'iso', 'on' => self::SCENARIO_CREATE, 'message' => Yii::t('app','ISO already exists.')],

            ['id', 'exist', 'targetClass' => Country::class, 'targetAttribute' => 'id', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE], 'message' => Yii::t('app','Country not found.')],

            [
                'url_slug',
                'unique',
                'targetClass' => Country::class,
                'targetAttribute' => 'url_slug',
                'filter' => function($query){ if ($this->id) { $query->andWhere(['<>','id', $this->id]); } },
                'skipOnEmpty' => true,
                'message' => Yii::t('app','URL Slug must be unique.'),
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'iso' => Yii::t('app', 'ISO'),
            'iso3' => Yii::t('app', 'ISO3'),
            'name' => Yii::t('app', 'Name'),
            'continent_code' => Yii::t('app', 'Continent Code'),
            'currency_code' => Yii::t('app', 'Currency Code'),
            'status' => Yii::t('app', 'Status'),
            'url_slug' => Yii::t('app', 'URL Slug'),
        ];
    }
}
