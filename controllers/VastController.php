<?php

namespace app\controllers;

use app\models\Creative;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class VastController extends Controller
{
    /**
     * Genera el XML VAST 3.0 dinámico (Video Linear o Imagen NonLinear).
     * Ruta: /vast/generate?id=123
     */
    public function actionGenerate(int $id)
    {
        $creative = Creative::findOne($id);

        // Verificamos que exista el creative y su fichero físico asociado
        if (!$creative || !$creative->assetFile) {
            throw new NotFoundHttpException();
        }

        // Configurar respuesta como XML
        \Yii::$app->response->format = Response::FORMAT_RAW;
        \Yii::$app->response->headers->add('Content-Type', 'text/xml');

        // --- 1. DATOS COMUNES ---
        $asset = $creative->assetFile;
        $mediaUrl = \Yii::$app->request->hostInfo . $asset->storage_path;
        $clickUrl = htmlspecialchars($creative->click_url ?? ''); // Escapar caracteres
        $adTitle = htmlspecialchars($creative->title);
        $mimeType = $asset->mime;

        // Dimensiones (Importantes para imágenes)
        $width = $asset->width ?? 0;
        $height = $asset->height ?? 0;

        // Construcción de la estructura interna del XML según el tipo
        $creativeXml = '';

        // --- 2. LÓGICA DE SELECCIÓN (VIDEO vs IMAGEN) ---
        if (str_starts_with($mimeType, 'video/')) {
            // === OPCIÓN A: VIDEO (Linear Ad) ===
            // Los videos usan <Linear> y <MediaFile>

            // Duración formateada HH:MM:SS
            $duration = gmdate('H:i:s', $asset->duration_sec ?? 15);

            $creativeXml = <<<XML
                <Linear>
                    <Duration>{$duration}</Duration>
                    <VideoClicks>
                        <ClickThrough><![CDATA[{$clickUrl}]]></ClickThrough>
                    </VideoClicks>
                    <MediaFiles>
                        <MediaFile delivery="progressive" type="{$mimeType}" width="{$width}" height="{$height}">
                            <![CDATA[{$mediaUrl}]]>
                        </MediaFile>
                    </MediaFiles>
                </Linear>
            XML;

        } else {
            // === OPCIÓN B: IMAGEN (NonLinear Ad) ===
            // Las imágenes usan <NonLinearAds> y <StaticResource>
            // minSuggestedDuration: Sugerimos al player mostrarla 15s si es un pase automático

            $creativeXml = <<<XML
                <NonLinearAds>
                    <NonLinear width="{$width}" height="{$height}" minSuggestedDuration="00:00:15">
                        <StaticResource creativeType="{$mimeType}">
                            <![CDATA[{$mediaUrl}]]>
                        </StaticResource>
                        <NonLinearClickThrough>
                            <![CDATA[{$clickUrl}]]>
                        </NonLinearClickThrough>
                    </NonLinear>
                </NonLinearAds>
            XML;
        }

        // --- 3. PLANTILLA VAST GLOBAL ---
        $finalXml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <VAST version="3.0">
                <Ad id="{$creative->id}">
                    <InLine>
                        <AdSystem>AdShowcase</AdSystem>
                        <AdTitle>{$adTitle}</AdTitle>
                        <Creatives>
                            <Creative>
                                {$creativeXml}
                            </Creative>
                        </Creatives>
                    </InLine>
                </Ad>
            </VAST>
        XML;

        return trim($finalXml);
    }
}