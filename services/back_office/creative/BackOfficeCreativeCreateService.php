<?php

declare(strict_types=1);

namespace app\services\back_office\creative;

use app\models\AssetFile;
use app\models\Creative;
use app\models\forms\back_office\CreativeForm;
use Yii;
use yii\web\UploadedFile;

final class BackOfficeCreativeCreateService
{
    private string $uploadPath;

    public function __construct()
    {
        $this->uploadPath = Yii::getAlias('@webroot/uploads');
    }

    public function create(CreativeForm $form): ?Creative
    {
        // 1. Instanciar el fichero subido
        $form->upload_asset = UploadedFile::getInstance($form, 'upload_asset');

        if (!$form->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // --- A. PROCESAR ASSET Y OBTENER ID ---
            // Si hay fichero nuevo, procesamos y obtenemos ID.
            if ($form->upload_asset) {
                $assetId = $this->processAssetFile($form->upload_asset);
                $form->asset_file_id = $assetId; // Asignamos el ID al formulario/modelo
            }

            // --- B. PROCESAR THUMBNAIL ---
            $finalThumbUrl = $this->processThumbnail($form->url_thumbnail);

            // --- C. GUARDAR CREATIVE ---
            $creative = new Creative();
            $creative->setAttributes([
                'hash' => Yii::$app->security->generateRandomString(16),
                'title' => $form->title,
                'asset_file_id' => $form->asset_file_id,
                'url_thumbnail' => $finalThumbUrl,
                'brand_id' => $form->brand_id,
                'agency_id' => $form->agency_id,
                'device_id' => $form->device_id,
                'country_id' => $form->country_id,
                'format_id' => $form->format_id,
                'sales_type_id' => $form->sales_type_id,
                'product_id' => $form->product_id,
                'language_id' => $form->language,
                'click_url' => $form->click_url,
                'workflow_status' => $form->workflow_status,
                'status' => $form->status,
                'user_id' => Yii::$app->user->id,
            ]);

            if (!$creative->save()) {
                $form->addErrors($creative->getErrors());
                throw new \Exception('Error saving creative');
            }

            $transaction->commit();
            $form->id = $creative->id;
            return $creative;

        } catch (\Exception $e) {
            $transaction->rollBack();
            // Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', Yii::t('app', 'Creation failed: ') . $e->getMessage());

            return null;
        }
    }

    /**
     * Procesa el fichero físico, guarda en ADSHOWCASE_asset_file y devuelve el ID.
     */
    private function processAssetFile(UploadedFile $file): int
    {
        // 1. Hash para deduplicación
        $hash = hash_file('sha256', $file->tempName);

        // 2. ¿Existe ya?
        $existing = AssetFile::findOne(['hash_sha256' => $hash]);
        if ($existing) {
            return $existing->id;
        }

        // 3. Extraer Metadatos (Requiere FFmpeg/ffprobe instalado en el servidor)
        $meta = $this->getVideoMetadata($file->tempName);

        // Validación extra de duración (30s) si es necesario
        if (($meta['duration'] ?? 0) > 30) {
            throw new \Exception(Yii::t('app', 'Video duration exceeds 30 seconds limit.'));
        }

        // 4. Guardar en disco
        $subDir = 'assets/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2);
        $targetDir = $this->uploadPath . '/' . $subDir;
        if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

        $fileName = $hash . '.' . $file->extension;
        $file->saveAs($targetDir . '/' . $fileName);

        // 5. Guardar en DB (Tabla AssetFile)
        $asset = new AssetFile();
        $asset->hash_sha256 = $hash;
        $asset->storage_path = '/uploads/' . $subDir . '/' . $fileName;
        $asset->mime = 'video/mp4';
        $asset->width = $meta['width'] ?? 0;
        $asset->height = $meta['height'] ?? 0;
        $asset->duration_sec = (int)($meta['duration'] ?? 0);

        if (!$asset->save()) {
            throw new \Exception('Failed to save asset file record.');
        }

        return $asset->id;
    }

    /**
     * Helper para extraer metadatos de video usando ffprobe
     */
    private function getVideoMetadata(string $filePath): array
    {
        // Comando para obtener ancho, alto y duración en JSON
        $cmd = sprintf(
            'ffprobe -v quiet -print_format json -show_format -show_streams "%s"',
            $filePath
        );

        $output = shell_exec($cmd);
        $data = json_decode($output, true);

        $videoStream = null;
        if (isset($data['streams'])) {
            foreach ($data['streams'] as $stream) {
                if (($stream['codec_type'] ?? '') === 'video') {
                    $videoStream = $stream;
                    break;
                }
            }
        }

        return [
            'width' => $videoStream['width'] ?? null,
            'height' => $videoStream['height'] ?? null,
            'duration' => $data['format']['duration'] ?? null,
        ];
    }

    /**
     * Detecta si es Base64 y guarda el archivo. Si es URL o vacío, maneja acorde.
     */
    private function processThumbnail(?string $inputData): ?string
    {
        if (empty($inputData)) return null;

        // ¿Es Base64?
        if (preg_match('/^data:image\/(\w+);base64,/', $inputData, $type)) {
            $data = substr($inputData, strpos($inputData, ',') + 1);
            $ext = strtolower($type[1]); // jpg, png, webp

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return null;

            $decoded = base64_decode($data);
            if ($decoded === false) return null;

            // Guardar fichero
            $fileName = 'thumb_' . Yii::$app->security->generateRandomString(16) . '.' . $ext;
            $subDir = 'thumbnails/' . date('Y/m');
            $targetDir = $this->uploadPath . '/' . $subDir;

            if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

            file_put_contents($targetDir . '/' . $fileName, $decoded);

            return '/uploads/' . $subDir . '/' . $fileName; // Retorna URL
        }

        // Si no es Base64, asumimos que es una URL válida (caso Update sin cambios)
        return $inputData;
    }
}