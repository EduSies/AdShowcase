<?php

namespace app\models\forms\back_office;

use app\helpers\SharedLinkHelper;
use app\models\Creative;
use yii\base\Model;
use Yii;

class SharedLinkForm extends Model
{
    public const FORM_NAME = 'shared-link-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public ?string $creative_hash = null;
    public ?string $ttl = null;
    public $creative_id = null;
    public $max_uses = null;
    public ?string $expires_at = null;
    public ?string $note = null;
    public ?string $hash = null;
    public ?int $id = null;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['creative_hash', 'ttl', 'creative_id', 'expires_at', 'max_uses'];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'hash', 'max_uses', 'expires_at', 'note'];
        $scenarios[self::SCENARIO_DELETE] = ['hash'];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['creative_hash'], 'string', 'min' => 16, 'max' => 16],
            [['creative_hash'], 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => Yii::t('app', 'Invalid hash format.')],
            [
                'creative_hash', 'exist',
                'targetClass' => Creative::class,
                'targetAttribute' => 'hash',
                'message' => Yii::t('app', 'Creative not found.'),
            ],

            [['ttl'], 'string'],
            [
                'ttl', 'in',
                'range' => array_keys(SharedLinkHelper::getTtlOptions()),
                'message' => Yii::t('app', 'Invalid expiration time.'),
            ],

            [['creative_hash', 'ttl'], 'required', 'on' => self::SCENARIO_CREATE],
            [['creative_id'], 'integer', 'on' => self::SCENARIO_CREATE],

            [['id'], 'integer', 'on' => self::SCENARIO_UPDATE],
            [['note'], 'string', 'max' => 500],

            [['expires_at'], 'safe'],

            [['max_uses'], 'default', 'value' => null],
            [['max_uses'], 'integer', 'min' => 1],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'creative_id' => Yii::t('app', 'Creative id'),
            'creative_hash' => Yii::t('app', 'Creative hash'),
            'ttl' => Yii::t('app', 'Expires in'),
            'max_uses' => Yii::t('app', 'Max Uses'),
            'expires_at' => Yii::t('app', 'Expiration Date'),
            'note' => Yii::t('app', 'Internal Note'),
        ];
    }
}