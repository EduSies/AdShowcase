<?php

declare(strict_types=1);

namespace app\services\back_office\creative;

use app\models\Creative;
use app\models\forms\back_office\CreativeForm;
use Yii;
use yii\web\UploadedFile;

final class BackOfficeCreativeCreateService
{
    use CreativeAssetTrait;

    public function __construct()
    {
        // Llamamos al inicializador del trait
        $this->initUploadPath();
    }

    public function create(CreativeForm $form): ?Creative
    {
        $this->tempFiles = []; // Reiniciar registro del trait

        $form->upload_asset = UploadedFile::getInstance($form, 'upload_asset');

        if (!$form->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Procesar Asset (Usa método del Trait)
            if ($form->upload_asset) {
                $assetId = $this->processAssetFile($form->upload_asset);
                $form->asset_file_id = $assetId;
            }

            // Procesar Thumbnail (Usa método del Trait)
            $finalThumbUrl = $this->processThumbnail($form->url_thumbnail);

            // Crear Entidad
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
                'language_id' => $form->language_id,
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

            $this->tempFiles = []; // Limpiar para no borrar

            return $creative;

        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->rollbackFiles();
            Yii::$app->session->setFlash('error', Yii::t('app', 'Creation failed: ') . $e->getMessage());

            return null;
        }
    }
}