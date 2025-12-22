<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;
use Yii;
use yii\helpers\FileHelper;

trait UserAssetTrait
{
    private string $uploadPath;
    private array $tempFiles = [];

    /**
     * Inicializa la ruta base de subidas.
     */
    private function initUploadPath(): void
    {
        $this->uploadPath = Yii::getAlias('@webroot/uploads/avatars');
        if (!is_dir($this->uploadPath)) {
            FileHelper::createDirectory($this->uploadPath, 0775, true);
        }
    }

    /**
     * Procesa el avatar con lógica de HASH (SHA-256) idéntica a Creatives.
     * Estructura resultante: /uploads/avatars/ab/cd/hash.ext
     * @param string|null $data Base64 string o URL
     * @return string|null La URL relativa del archivo guardado
     */
    private function processAvatar(?string $data): ?string
    {
        if (empty($data)) {
            return null;
        }

        // Si ya es una ruta de archivo (empieza por /uploads/), no hacemos nada
        if (str_starts_with($data, '/uploads/')) {
            return $data;
        }

        // Si es base64 (data:image/...)
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc.

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                throw new \Exception(Yii::t('app', 'Invalid image type.'));
            }

            $decoded = base64_decode($data);
            if ($decoded === false) {
                throw new \Exception(Yii::t('app', 'Base64 decode failed.'));
            }

            // Generamos el Hash del contenido de la imagen
            $hash = hash('sha256', $decoded);

            // Creamos la estructura de carpetas basada en el hash (Igual que Creative)
            // Ejemplo: Hash "a1b2c3..." -> carpeta "a1/b2"
            $subDir = substr($hash, 0, 2) . '/' . substr($hash, 2, 2);
            $targetDir = $this->uploadPath . '/' . $subDir;

            if (!is_dir($targetDir)) {
                FileHelper::createDirectory($targetDir, 0775, true);
            }

            // El nombre del archivo es el hash + extensión
            $filename = $hash . '.' . $type;

            $relativePath = '/uploads/avatars/' . $subDir . '/' . $filename;
            $absolutePath = $targetDir . '/' . $filename;

            // Guardamos SOLO si no existe (Deduplicación física)
            if (!file_exists($absolutePath)) {
                if (file_put_contents($absolutePath, $decoded) !== false) {
                    $this->tempFiles[] = $absolutePath; // Registramos para rollback
                } else {
                    throw new \Exception(Yii::t('app', 'Failed to save avatar file.'));
                }
            }

            return $relativePath;
        }

        return null;
    }

    /**
     * Elimina los archivos físicos creados en la transacción actual si hubo error.
     */
    private function rollbackFiles(): void
    {
        foreach ($this->tempFiles as $filePath) {
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        $this->tempFiles = [];
    }

    /**
     * Borra el avatar antiguo del disco SOLO si ningún otro usuario lo usa.
     */
    private function deleteOrphanedAvatar(string $url): void
    {
        if (!str_starts_with($url, '/uploads/')) {
            return;
        }

        // Deduplicación lógica: Verificar si otro usuario tiene el mismo hash/foto
        $count = User::find()
            ->where(['avatar_url' => $url])
            ->count();

        if ($count > 0) {
            return; // Todavía se usa por alguien más
        }

        $physicalPath = Yii::getAlias('@webroot') . $url;

        if (file_exists($physicalPath)) {
            @unlink($physicalPath);
            // Llamada a la limpieza recursiva segura
            $this->cleanupEmptyDirectories($physicalPath);
        }
    }

    /**
     * Borra carpetas vacías en cascada hacia arriba de forma segura.
     * Estructura: uploads/avatars/AB/CD/file.ext
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
     */
    private function safeRemoveDirectory(string $dir): bool
    {
        if (!is_dir($dir) || !is_writable($dir)) return false;

        $items = scandir($dir);
        $realItems = array_filter($items, fn($i) => $i !== '.' && $i !== '..');

        if (count($realItems) > 0) return false;

        return @rmdir($dir);
    }

    /**
     * Assign RBAC role matching $type to given User.
     *
     * @param User   $user
     * @param string $type Role name (same as user type)
     *
     * @return string|null Error message on failure, or null on success.
     */
    private function syncRbacRole(User $user, string $type): ?string
    {
        if ($type === '') return null;

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($type);

        if ($role === null) {
            $message = Yii::t('app', 'RBAC role "{role}" not found while creating user #{id}', [
                'role' => $type,
                'id' => $user->id,
            ]);
            Yii::warning($message, __METHOD__);

            return $message;
        }

        // Por si acaso alguien ha asignado algo antes (no debería en create), eliminamos cualquier permiso existente
        $auth->revokeAll((string) $user->id);
        $auth->assign($role, (string) $user->id);

        return null;
    }
}