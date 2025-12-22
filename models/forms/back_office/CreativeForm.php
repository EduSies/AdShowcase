<?php

namespace app\models\forms\back_office;

use app\helpers\StatusHelper;
use app\models\Creative;
use app\models\LanguageLocale;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class CreativeForm extends Model
{
    public const FORM_NAME = 'creative-form';

    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_DELETE = 'delete';

    // --- Propiedades del Modelo (Base de Datos) ---
    public $id = null;
    public ?string $hash = null;
    public ?string $title = null;
    public $brand_id = null;
    public $agency_id = null;
    public $device_id = null;
    public $country_id = null;
    public $format_id = null;
    public $sales_type_id = null;
    public $product_id = null;
    public $language_id = null;

    public ?string $click_url = null;

    // Valores por defecto
    public string $workflow_status = StatusHelper::WORKFLOW_DRAFT;
    public string $status = StatusHelper::STATUS_ACTIVE;

    // --- Propiedades Virtuales ---
    /** @var UploadedFile|null El archivo principal (Video o Imagen) */
    public $upload_asset;

    // Thumbnail: Input (Base64) / Output (URL)
    public ?string $url_thumbnail = null;

    // ID generado para la BD
    public ?int $asset_file_id = null;

    public ?string $preview_asset_url = null;
    public ?string $preview_asset_mime = null;

    public function formName(): string
    {
        return self::FORM_NAME;
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        // Escenario CREATE:
        // - upload_asset es obligatorio (el video).
        // - url_thumbnail es obligatorio (el Base64 de la imagen).
        // - asset_file_id NO se incluye aquí porque lo calcula el servicio.
        $scenarios[self::SCENARIO_CREATE] = [
            'upload_asset', 'url_thumbnail',
            'title', 'brand_id', 'agency_id',
            'device_id', 'country_id', 'format_id', 'sales_type_id', 'product_id',
            'language_id', 'click_url', 'workflow_status', 'status'
        ];

        // Escenario UPDATE:
        // - Todo es "safe". upload_asset es opcional.
        // - Incluimos asset_file_id por si se mantiene el existente.
        $scenarios[self::SCENARIO_UPDATE] = [
            'id', 'upload_asset', 'url_thumbnail', 'asset_file_id',
            'title', 'brand_id', 'agency_id',
            'device_id', 'country_id', 'format_id', 'sales_type_id', 'product_id',
            'language_id', 'click_url', 'workflow_status', 'status'
        ];

        // Escenario DELETE: Solo ID
        $scenarios[self::SCENARIO_DELETE] = ['id'];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            // --- REGLAS DE SUBIDA DE VIDEO (Estricto: MP4, Max 25MB) ---
            [['upload_asset'], 'required', 'on' => self::SCENARIO_CREATE],
            [['upload_asset'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['mp4', 'jpg', 'jpeg', 'png', 'webp'],
                'checkExtensionByMimeType' => true,
                'maxSize' => 25 * 1024 * 1024,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            ['upload_asset', 'validateAssetConstraints'],

            // --- REGLAS DE THUMBNAIL (Base64 o URL) ---
            ['url_thumbnail', 'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
                'message' => Yii::t('app', 'Please upload and crop a thumbnail image.')
            ],
            ['url_thumbnail', 'string'],
            ['url_thumbnail', 'validateThumbnailSize'],

            // --- 3. ID DE ASSET ---
            ['asset_file_id', 'integer'],

            // --- 4. HASH VALIDATION (COPIADO DE COUNTRYFORM) ---
            ['hash', 'string', 'min' => 16, 'max' => 16],
            ['hash', 'match', 'pattern' => '/^[A-Za-z0-9_-]{16}$/', 'message' => Yii::t('app', 'Invalid hash format.')],
            [
                'hash', 'unique',
                'targetClass' => Creative::class,
                'targetAttribute' => 'hash',
                'filter' => function ($q) { if ($this->id) { $q->andWhere(['<>', 'id', $this->id]); } },
                'when' => fn() => !empty($this->hash),
                'skipOnEmpty' => true,
            ],

            // --- 5. CAMPOS DE TEXTO REQUERIDOS ---
            [[
                'title', 'brand_id', 'agency_id', 'device_id', 'country_id',
                'format_id', 'sales_type_id', 'product_id', 'language_id',
                'workflow_status', 'status', 'click_url'
            ], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            // --- 5. REQUERIDO EN DELETE/UPDATE ---
            [['id'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_DELETE]],

            // --- 6. TIPOS DE DATOS ---
            [[
                'id', 'brand_id', 'agency_id', 'device_id', 'country_id',
                'format_id', 'sales_type_id', 'product_id'
            ], 'integer', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            // Strings y Formatos
            [['title'], 'trim'],
            [['title'], 'string', 'max' => 255],

            // Click URL sí tiene límite de 500 chars
            [['click_url'], 'trim'],
            [['click_url'], 'string', 'max' => 500],
            [['click_url'], 'url', 'defaultScheme' => 'https'],

            [['language_id'], 'integer'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => LanguageLocale::class, 'targetAttribute' => ['language_id' => 'id']],

            // --- 7. VALIDACIONES DE RANGO (Enums) ---
            ['status', 'in',
                'range' => StatusHelper::getStatusRange(3),
                'message' => Yii::t('app', 'Invalid status.'),
            ],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],

            ['workflow_status', 'in',
                'range' => StatusHelper::getWorkflowStatusRange(),
                'message' => Yii::t('app', 'Invalid workflow status.'),
            ],
            ['workflow_status', 'default', 'value' => StatusHelper::WORKFLOW_DRAFT],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hash' => Yii::t('app', 'Hash'),
            'upload_asset' => Yii::t('app', 'Video or Image File'),
            'asset_file_id' => Yii::t('app', 'Asset File ID'),
            'url_thumbnail' => Yii::t('app', 'Thumbnail gallery'),
            'title' => Yii::t('app', 'Title'),
            'brand_id' => Yii::t('app', 'Brand'),
            'agency_id' => Yii::t('app', 'Agency'),
            'device_id' => Yii::t('app', 'Device'),
            'country_id' => Yii::t('app', 'Country'),
            'format_id' => Yii::t('app', 'Format'),
            'sales_type_id' => Yii::t('app', 'Sales Type'),
            'product_id' => Yii::t('app', 'Product'),
            'language_id' => Yii::t('app', 'Language'),
            'click_url' => Yii::t('app', 'Destination URL'),
            'workflow_status' => Yii::t('app', 'Workflow Status'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Valida reglas específicas según si es imagen o video.
     */
    public function validateAssetConstraints($attribute, $params)
    {
        if (!$this->$attribute instanceof UploadedFile) {
            return;
        }

        $file = $this->$attribute;
        $ext = strtolower($file->extension);
        $size = $file->size;

        // Reglas para IMAGEN
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $limit = 2 * 1024 * 1024; // 2MB
            if ($size > $limit) {
                $this->addError($attribute, Yii::t('app', 'Images must not exceed 2MB.'));
            }
        }
        // Reglas para VIDEO
        elseif ($ext === 'mp4') {
            $limit = 25 * 1024 * 1024; // 25MB
            if ($size > $limit) {
                $this->addError($attribute, Yii::t('app', 'Videos must not exceed 25MB.'));
            }
        }
    }

    /**
     * Valida que el string Base64 del thumbnail no exceda el tamaño límite (2MB).
     * Fórmula: El tamaño en bytes es aprox (longitud_string * 3) / 4.
     */
    public function validateThumbnailSize($attribute, $params)
    {
        $value = $this->$attribute;

        // Si está vacío o es una URL corta (ruta de archivo existente), no validamos tamaño
        if (empty($value) || strlen($value) < 255 || !str_starts_with($value, 'data:image')) {
            return;
        }

        // Cálculo aproximado del tamaño en Bytes desde Base64
        // Quitamos la cabecera "data:image/jpeg;base64," para ser más precisos,
        // aunque calcular sobre el total también sirve como aproximación segura.
        $sizeInBytes = (int) (strlen($value) * (3/4));

        // Padding: el '=' al final indica bytes vacíos, restamos si queremos precisión milimétrica,
        // pero para un límite de seguridad, la estimación de arriba es suficiente.

        $limitBytes = 2 * 1024 * 1024; // 2MB

        if ($sizeInBytes > $limitBytes) {
            $this->addError($attribute, Yii::t('app', 'The thumbnail image is too large. Max allowed size is 2MB.'));
        }
    }
}