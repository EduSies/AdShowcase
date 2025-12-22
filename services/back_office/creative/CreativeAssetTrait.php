<?php

declare(strict_types=1);

namespace app\services\back_office\creative;

use app\models\AssetFile;
use app\models\Creative;
use Yii;
use yii\web\UploadedFile;

/**
 * Trait para gestionar la subida, procesamiento y limpieza de assets creativos.
 */
trait CreativeAssetTrait
{
    private string $uploadPath;
    private array $tempFiles = [];

    /**
     * Inicializa el path de subida. Debe llamarse en el constructor del servicio.
     */
    private function initUploadPath(): void
    {
        $this->uploadPath = Yii::getAlias('@webroot/uploads');
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0775, true);
        }
    }

    /**
     * Procesa el fichero físico (Imagen o Video), deduplica y devuelve ID de AssetFile.
     */
    private function processAssetFile(UploadedFile $file): int
    {
        $hash = hash_file('sha256', $file->tempName);

        // 1. Deduplicación DB
        $existing = AssetFile::findOne(['hash_sha256' => $hash]);
        if ($existing) {
            return $existing->id;
        }

        // 2. Metadatos
        $meta = $this->getVideoMetadata($file->tempName);
        if (($meta['duration'] ?? 0) > 30) {
            throw new \Exception(Yii::t('app', 'Video duration exceeds 30 seconds limit.'));
        }

        // Si es imagen, completamos dimensiones si no vinieron de getVideoMetadata (ffprobe a veces falla con imgs)
        if (str_starts_with($file->type, 'image/')) {
            $imageInfo = getimagesize($file->tempName);
            if ($imageInfo) {
                $meta['width'] = $imageInfo[0];
                $meta['height'] = $imageInfo[1];
            }
        }

        // 3. Guardar en disco (con estructura sharding)
        $subDir = 'assets/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2);
        $targetDir = $this->uploadPath . '/' . $subDir;
        if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

        $fileName = $hash . '.' . $file->extension;
        $fullPath = $targetDir . '/' . $fileName;

        // Deduplicación física: solo escribimos si no existe
        if (!file_exists($fullPath)) {
            if ($file->saveAs($fullPath)) {
                $this->tempFiles[] = $fullPath; // Registrar para rollback
            } else {
                throw new \Exception('Failed to write asset file to disk.');
            }
        }

        // 4. Guardar AssetFile
        $asset = new AssetFile();
        $asset->hash_sha256 = $hash;
        $asset->storage_path = '/uploads/' . $subDir . '/' . $fileName;
        $asset->mime = $file->type ?: 'application/octet-stream'; // Asegurar mime
        $asset->width = $meta['width'] ?? 0;
        $asset->height = $meta['height'] ?? 0;
        $asset->duration_sec = (int)($meta['duration'] ?? 0);

        if (!$asset->save()) {
            throw new \Exception('Failed to save asset file record.');
        }

        return $asset->id;
    }

    /**
     * Procesa el thumbnail (Base64 -> Fichero).
     * Devuelve la URL relativa o el input original si no hubo cambios.
     */
    private function processThumbnail(?string $inputData): ?string
    {
        if (empty($inputData)) return null;

        // Detectar si es Base64
        if (preg_match('/^data:image\/(\w+);base64,/', $inputData, $type)) {
            $data = substr($inputData, strpos($inputData, ',') + 1);
            $ext = strtolower($type[1]);

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return null;

            $decoded = base64_decode($data);
            if ($decoded === false) return null;

            $hash = hash('sha256', $decoded);
            $subDir = 'thumbnails/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2);
            $targetDir = $this->uploadPath . '/' . $subDir;

            if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

            $fileName = $hash . '.' . $ext;
            $fullPath = $targetDir . '/' . $fileName;

            if (!file_exists($fullPath)) {
                if (file_put_contents($fullPath, $decoded) !== false) {
                    $this->tempFiles[] = $fullPath;
                } else {
                    return null;
                }
            }

            return '/uploads/' . $subDir . '/' . $fileName;
        }

        return $inputData; // Retorna la URL original si no es base64
    }

    /**
     * Extrae metadatos usando ffprobe.
     */
    private function getVideoMetadata(string $filePath): array
    {
        $cmd = sprintf(
            'ffprobe -v quiet -print_format json -show_format -show_streams %s',
            escapeshellarg($filePath)
        );

        $output = shell_exec($cmd);

        if (!$output) {
            return ['width' => 0, 'height' => 0, 'duration' => 0];
        }

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

    /**
     * Elimina los archivos físicos creados en la transacción actual.
     */
    private function rollbackFiles(): void
    {
        foreach ($this->tempFiles as $filePath) {
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
    }

    /**
     * Borra el AssetFile antiguo de BD y Disco SOLO si ninguna otra creatividad lo usa.
     */
    private function deleteOrphanedAsset(int $assetId): void
    {
        $count = Creative::find()->where(['asset_file_id' => $assetId])->count();
        if ($count > 0) return;

        $asset = AssetFile::findOne($assetId);
        if (!$asset) return;

        $physicalPath = Yii::getAlias('@webroot') . $asset->storage_path;

        if (file_exists($physicalPath)) {
            @unlink($physicalPath);
            // Llamada a la limpieza recursiva segura
            $this->cleanupEmptyDirectories($physicalPath);
        }

        $asset->delete();
    }

    /**
     * Borra el thumbnail antiguo del disco SOLO si ninguna otra creatividad lo usa.
     */
    private function deleteOrphanedThumbnail(string $url): void
    {
        if (!str_starts_with($url, '/uploads/')) return;

        $count = Creative::find()->where(['url_thumbnail' => $url])->count();
        if ($count > 0) return;

        $physicalPath = Yii::getAlias('@webroot') . $url;

        if (file_exists($physicalPath)) {
            @unlink($physicalPath);
            // Llamada a la limpieza recursiva segura
            $this->cleanupEmptyDirectories($physicalPath);
        }
    }

    /**
     * Borra carpetas vacías en cascada hacia arriba de forma segura.
     * Estructura: uploads/assets/AB/CD/file.ext
     */
    private function cleanupEmptyDirectories(string $filePath): void
    {
        // Obtener directorio Nivel 2 (el inmediato al archivo): .../AB/CD
        $dirLevel2 = dirname($filePath);

        // Intentamos borrar el Nivel 2
        if ($this->safeRemoveDirectory($dirLevel2)) {

            // SOLO si se borró el Nivel 2 con éxito, intentamos con el Nivel 1
            // Obtener directorio Nivel 1 (el padre): .../AB
            $dirLevel1 = dirname($dirLevel2);

            $this->safeRemoveDirectory($dirLevel1);
        }
    }

    /**
     * Verifica estrictamente si un directorio está vacío y lo borra.
     * Retorna true si se borró, false si no estaba vacío o hubo error.
     */
    private function safeRemoveDirectory(string $dir): bool
    {
        if (!is_dir($dir) || !is_writable($dir)) {
            return false;
        }

        // 1. ESCANEO ESTRICTO
        $items = scandir($dir);

        // Filtramos '.' y '..'
        $realItems = array_filter($items, function($item) {
            return $item !== '.' && $item !== '..';
        });

        // Si queda CUALQUIER cosa (archivos o carpetas), NO BORRAMOS.
        if (count($realItems) > 0) {
            return false;
        }

        // 2. BORRADO SEGURO
        // rmdir() nativamente devuelve false y emite Warning si el directorio no está vacío.
        // El @ silencia el warning, pero la función sigue protegiendo el borrado.
        return @rmdir($dir);
    }
}