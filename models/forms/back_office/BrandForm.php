<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use Yii;
use yii\base\Model;

final class BrandForm extends Model
{
    public const FORM_MANE = 'brand-form';

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

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    public function formName(): string
    {
        return self::FORM_MANE;
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
            // create
            [['name', 'url_name', 'status'], 'required', 'on' => self::SCENARIO_CREATE],

            // update
            [['id', 'name', 'url_name' ,'status'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'integer', 'on' => self::SCENARIO_UPDATE],

            // delete
            [['id'], 'required', 'on' => self::SCENARIO_DELETE],
            [['id'], 'integer', 'on' => self::SCENARIO_DELETE],

            // Normalize/trim
            [['name','url_name'], 'trim'],

            // Hash format (exactly 16 URL-safe chars). If empty, we’ll auto-generate in beforeValidate().
            ['hash', 'string', 'length' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => 'Hash must be 16 chars: letters, numbers, "-" or "_"'],

            // Strings length
            [['name', 'url_name'], 'string', 'max' => 255],

            // Slug format (lowercase, numbers and dashes)
            ['url_name', 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'message' => 'Use lowercase letters, numbers and dashes only.'],

            // Status constraint
            ['status', 'in', 'range' => [
                StatusHelper::STATUS_ACTIVE,
                StatusHelper::STATUS_ARCHIVED,
                StatusHelper::STATUS_PENDING,
            ]],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],
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