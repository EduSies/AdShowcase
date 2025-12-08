<?php

namespace app\models\forms\back_office;

use yii\base\Model;
use app\models\Format;
use app\helpers\StatusHelper;
use Yii;

final class FormatForm extends Model
{
    public const FORM_NAME = 'format-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public ?int $id = null;
    public ?string $hash = null;
    public ?string $name = null;
    public ?string $format = null;
    public ?string $family = null;
    public ?string $experience = null;
    public ?string $subtype = null;
    public string $status = StatusHelper::STATUS_ACTIVE;
    public ?string $url_slug = null;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['name', 'format', 'family', 'experience', 'subtype', 'status', 'url_slug'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'hash', 'name', 'format', 'family', 'experience', 'subtype', 'status', 'url_slug'];
        $scenarios[self::SCENARIO_DELETE] = ['hash'];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['name', 'format', 'family', 'experience', 'status', 'url_slug'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'name', 'format', 'family', 'experience', 'status', 'url_slug'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],

            [['id'], 'integer', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE]],
            [['name'], 'string', 'max' => 150],
            [['format', 'family', 'experience', 'subtype'], 'string', 'max' => 100],
            [['url_slug'], 'string', 'max' => 255],

            [['name', 'format', 'family', 'experience', 'subtype', 'status', 'url_slug'], 'trim'],

            ['hash', 'string', 'min' => 16, 'max' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => Yii::t('app', 'Invalid hash format.')],
            [
                'hash', 'unique',
                'targetClass' => Format::class,
                'targetAttribute' => 'hash',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } },
                'when' => fn() => !empty($this->hash),
                'skipOnEmpty' => true,
            ],

            ['status', 'in',
                'range' => StatusHelper::getRange(3),
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => $this->status],

            ['url_slug', 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'message' => Yii::t('app', 'Use lowercase letters, numbers and dashes only.')],

            ['id', 'exist', 'targetClass' => Format::class, 'targetAttribute' => 'id', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE]],

            ['url_slug', 'validateUrlSlugUnique', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'format' => Yii::t('app', 'Format'),
            'family' => Yii::t('app', 'Family'),
            'experience' => Yii::t('app', 'Experience'),
            'subtype' => Yii::t('app', 'Subtype'),
            'status' => Yii::t('app', 'Status'),
            'url_slug' => Yii::t('app', 'URL Slug'),
        ];
    }

    /**
     * Custom validator to ensure url_slug is unique (excluding current ID on update).
     */
    public function validateUrlSlugUnique(): void
    {
        if ($this->hasErrors() || $this->url_slug === null || $this->url_slug === '') {
            return;
        }
        $query = Format::find()->where(['url_slug' => $this->url_slug]);
        if ($this->id) {
            $query->andWhere(['<>', 'id', $this->id]);
        }
        if ($query->exists()) {
            $this->addError('url_slug', Yii::t('app', 'This slug is already in use.'));
        }
    }
}