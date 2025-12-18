<?php

declare(strict_types=1);

namespace app\services\back_office\creative;

use app\models\AssetFile;
use app\models\Creative;
use app\models\forms\back_office\CreativeForm;
use Yii;
use yii\web\UploadedFile;

final class BackOfficeCreativeUpdateService
{
    private string $uploadPath;

    public function __construct()
    {
        $this->uploadPath = Yii::getAlias('@webroot/uploads');
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0775, true);
        }
    }

    public function update(int $hash, CreativeForm $form): bool
    {
        $creative = Creative::findOne(['hash' => $hash]);

        if (!$creative) {
            $form->addErrors($creative->getErrors());
            return false;
        }

        // 1. Instanciar el posible nuevo fichero
        $form->upload_asset = UploadedFile::getInstance($form, 'upload_asset');

        // Validamos el form (incluye reglas de ficheros si se han subido)
        if (!$form->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // --- A. GESTIÓN DEL ASSET PRINCIPAL (Condicional) ---
            // Solo si el usuario subió un archivo nuevo, lo procesamos.
            if ($form->upload_asset !== null) {
                $newAssetId = $this->processAssetFile($form->upload_asset);
                $creative->asset_file_id = $newAssetId; // Actualizamos la FK
            }
            // Si es null, mantenemos el $creative->asset_file_id que ya tenía en BD.

            // --- B. GESTIÓN DEL THUMBNAIL (Condicional) ---
            // El form trae 'url_thumbnail'. Puede ser una URL existente o un Base64 nuevo.
            $processedThumb = $this->processThumbnail($form->url_thumbnail);

            // Si nos devuelve una ruta nueva (porque era base64), actualizamos.
            // Si nos devuelve null (era una URL o estaba vacío), mantenemos la anterior si es válida.
            if ($processedThumb) {
                $creative->url_thumbnail = $processedThumb;
            }

            // --- C. ACTUALIZAR METADATOS ---
            $creative->title = $form->title;
            $creative->brand_id = $form->brand_id;
            $creative->agency_id = $form->agency_id;
            $creative->device_id = $form->device_id;
            $creative->country_id = $form->country_id;
            $creative->format_id = $form->format_id;
            $creative->sales_type_id = $form->sales_type_id;
            $creative->product_id = $form->product_id;
            $creative->language_id = $form->language_id;
            $creative->click_url = $form->click_url;
            $creative->workflow_status = $form->workflow_status;
            $creative->status = $form->status;
            // user_id y hash no se suelen cambiar en update

            if (!$creative->save()) {
                $form->addErrors($creative->getErrors());
                throw new \Exception('Error updating creative.');
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            // Yii::error($e->getMessage()); // Descomentar para debug
            Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed: ') . $e->getMessage());

            return false;
        }
    }

    /**
     * Procesa el fichero físico (Imagen o Video), deduplica y devuelve ID.
     * (Misma lógica que en CreateService)
     */
    private function processAssetFile(UploadedFile $file): int
    {
        // 1. Hash
        $hash = hash_file('sha256', $file->tempName);

        // 2. Deduplicación
        $existing = AssetFile::findOne(['hash_sha256' => $hash]);
        if ($existing) {
            return $existing->id;
        }

        // 3. Metadatos según tipo
        $mime = mime_content_type($file->tempName);
        $meta = ['width' => 0, 'height' => 0, 'duration' => 0];

        if (str_starts_with($mime, 'video/')) {
            $meta = $this->getVideoMetadata($file->tempName);
            // Validación extra 30s
            if (($meta['duration'] ?? 0) > 30) {
                throw new \Exception(Yii::t('app', 'Video duration exceeds 30 seconds limit.'));
            }
        } elseif (str_starts_with($mime, 'image/')) {
            $imageInfo = getimagesize($file->tempName);
            if ($imageInfo) {
                $meta['width'] = $imageInfo[0];
                $meta['height'] = $imageInfo[1];
            }
        }

        // 4. Guardar Fichero
        $subDir = 'assets/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2);
        $targetDir = $this->uploadPath . '/' . $subDir;
        if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

        $fileName = $hash . '.' . $file->extension;
        $file->saveAs($targetDir . '/' . $fileName);

        // 5. Guardar AssetFile
        $asset = new AssetFile();
        $asset->hash_sha256 = $hash;
        $asset->storage_path = '/uploads/' . $subDir . '/' . $fileName;
        $asset->mime = $mime;
        $asset->width = $meta['width'];
        $asset->height = $meta['height'];
        $asset->duration_sec = (int)$meta['duration'];

        if (!$asset->save()) {
            throw new \Exception('Failed to save asset file record.');
        }

        return $asset->id;
    }

    /**
     * Procesa el thumbnail:
     * - Si es Base64 -> Lo guarda y devuelve la nueva URL.
     * - Si es URL normal o vacío -> Devuelve null (no hay cambios que aplicar).
     */
    private function processThumbnail(?string $inputData): ?string
    {
        if (empty($inputData)) return null;

        // Detectar si es Base64 (nuevo recorte)
        if (preg_match('/^data:image\/(\w+);base64,/', $inputData, $type)) {
            $data = substr($inputData, strpos($inputData, ',') + 1);
            $ext = strtolower($type[1]); // jpg, png, webp

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return null;

            $decoded = base64_decode($data);
            if ($decoded === false) return null;

            // Guardar nuevo thumb
            $fileName = 'thumb_' . Yii::$app->security->generateRandomString(16) . '.' . $ext;
            $subDir = 'thumbnails/' . date('Y/m');
            $targetDir = $this->uploadPath . '/' . $subDir;

            if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

            file_put_contents($targetDir . '/' . $fileName, $decoded);

            return '/uploads/' . $subDir . '/' . $fileName;
        }

        // Si no es base64, asumimos que es la URL antigua, no hay que actualizar nada.
        return null;
    }

    /**
     * Helper FFmpeg
     */
    private function getVideoMetadata(string $filePath): array
    {
        // Requiere FFmpeg instalado en el sistema
        $cmd = sprintf('ffprobe -v quiet -print_format json -show_format -show_streams "%s"', $filePath);
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
            'width' => $videoStream['width'] ?? 0,
            'height' => $videoStream['height'] ?? 0,
            'duration' => $data['format']['duration'] ?? 0,
        ];
    }
}