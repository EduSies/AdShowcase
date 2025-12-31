<?php

declare(strict_types=1);

namespace app\helpers;

use Yii;

class PreviewHelper
{
    /**
     * Genera un string XML VAST 3.0 basado en los datos proporcionados.
     */
    public static function generateVast(
        int $creativeId,
        string $adTitle,
        string $clickUrl,
        string $mediaUrl,
        string $mimeType,
        int $width,
        int $height,
        int $durationSec = 15
    ): string {

        // Escapar datos para XML
        $clickUrl = htmlspecialchars($clickUrl);
        $adTitle = htmlspecialchars($adTitle);

        $creativeXml = '';

        if (str_starts_with($mimeType, 'video/')) {
            // === VIDEO (Linear Ad) ===
            $duration = gmdate('H:i:s', $durationSec);

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
            // === IMAGEN (NonLinear Ad) ===
            // minSuggestedDuration: 15s por defecto
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

        // --- PLANTILLA VAST GLOBAL ---
        $finalXml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <VAST version="3.0">
                <Ad id="{$creativeId}">
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