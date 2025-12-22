<?php

declare(strict_types=1);

namespace app\services\back_office\creative;

use app\models\Creative;
use Yii;

final class BackOfficeCreativeDeleteService
{
    // Usamos el Trait para poder borrar los archivos físicos
    use CreativeAssetTrait;

    public function __construct()
    {
        // Inicializamos el path por si el Trait lo necesita
        $this->initUploadPath();
    }

    /**
     * Elimina un Creative y sus archivos asociados si quedan huérfanos.
     */
    public function delete(string $hash): bool
    {
        $creative = Creative::findOne(['hash' => $hash]);

        if (!$creative) {
            return false;
        }

        // Capturamos los datos ANTES de borrar el registro de la BD
        $assetId = $creative->asset_file_id;
        $thumbUrl = $creative->url_thumbnail;

        // Borramos el registro de la base de datos
        if ($creative->delete() === false) {
            return false;
        }

        // LIMPIEZA DE ARCHIVOS (Post-Delete)
        // Intentamos borrar los archivos físicos si ya nadie más los usa
        try {
            if ($assetId) {
                $this->deleteOrphanedAsset($assetId);
            }
            if ($thumbUrl) {
                $this->deleteOrphanedThumbnail($thumbUrl);
            }
        } catch (\Exception $e) {
            // Si falla el borrado de archivos, lo logueamos pero no fallamos la acción
            // porque el registro de BD ya se borró correctamente
            Yii::error('Error cleaning up files after delete: ' . $e->getMessage());
        }

        return true;
    }
}