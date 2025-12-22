<?php

declare(strict_types=1);

namespace app\services\back_office\creative;

use app\models\Creative;
use app\models\forms\back_office\CreativeForm;
use Yii;
use yii\web\UploadedFile;

final class BackOfficeCreativeUpdateService
{
    use CreativeAssetTrait;

    public function __construct()
    {
        $this->initUploadPath();
    }

    public function update(string $hash, CreativeForm $form): bool
    {
        $this->tempFiles = [];

        $creative = Creative::findOne(['hash' => $hash]);

        if (!$creative) {
            return false;
        }

        $oldAssetId = $creative->asset_file_id;
        $oldThumbUrl = $creative->url_thumbnail;

        $form->upload_asset = UploadedFile::getInstance($form, 'upload_asset');

        if (!$form->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Asset (Usa método del Trait)
            if ($form->upload_asset !== null) {
                $newAssetId = $this->processAssetFile($form->upload_asset);
                $creative->asset_file_id = $newAssetId;
            }

            // Thumbnail (Usa método del Trait)
            $processedThumb = $this->processThumbnail($form->url_thumbnail);
            if ($processedThumb) {
                $creative->url_thumbnail = $processedThumb;
            }

            // Actualizar datos
            $creative->setAttributes([
                'title' => $form->title,
                'brand_id' => $form->brand_id,
                'agency_id' => $form->agency_id,
                'device_id' => $form->device_id,
                'country_id' => $form->country_id,
                'format_id' => $form->format_id,
                'sales_type_id' => $form->sales_type_id,
                'product_id' => $form->product_id,
                'language_id' => $form->language_id,
                'click_url' => $form->click_url,
                'workflow_status' => $form->workflow_status,
                'status' => $form->status,
            ]);

            if (!$creative->save()) {
                $form->addErrors($creative->getErrors());
                throw new \Exception('Error updating creative.');
            }

            $transaction->commit();
            $this->tempFiles = [];

            // --- LIMPIEZA DE ARCHIVOS ANTIGUOS (POST-COMMIT) ---
            try {
                // Si el ID del asset ha cambiado, verificamos si el antiguo quedó huérfano
                if ($oldAssetId && $oldAssetId !== $creative->asset_file_id) {
                    $this->deleteOrphanedAsset($oldAssetId);
                }

                // Si la URL del thumbnail ha cambiado
                if ($oldThumbUrl && $oldThumbUrl !== $creative->url_thumbnail) {
                    $this->deleteOrphanedThumbnail($oldThumbUrl);
                }
            } catch (\Exception $e) {
                // Si falla el borrado de limpieza, lo logueamos pero NO fallamos la petición del usuario.
                Yii::error('Error cleaning up orphaned assets: ' . $e->getMessage());
            }

            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->rollbackFiles();

            Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed: ') . $e->getMessage());

            return false;
        }
    }
}