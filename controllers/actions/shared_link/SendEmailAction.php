<?php

namespace app\controllers\actions\shared_link;

use app\services\email\EmailSenderService;
use Yii;
use yii\helpers\Html;
use yii\web\Response;

class SendEmailAction extends BaseSharedLinkAction
{
    public string|bool $layout = false;
    public ?string $can = 'share.manage';

    public function run()
    {
        $this->ensureCan($this->can);
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;

        // Recoger datos del POST
        $emailTo = $request->post('email');
        $sharedUrl = $request->post('url');
        $qrSrc = $request->post('qr_src');
        $title = $request->post('title');
        $format = $request->post('format');
        $agency = $request->post('agency');

        if (!$emailTo || !$sharedUrl) {
            return ['success' => false, 'message' => Yii::t('app', 'Missing email or URL.')];
        }

        try {
            // Construir el HTML del cuerpo del correo
            $htmlContent = $this->buildEmailBody($title, $format, $agency, $qrSrc, $sharedUrl);

            // Usar el Servicio de Email existente
            $emailService = new EmailSenderService();

            $subject = Yii::t('app', 'AdShowcase Preview Shared: {title}', ['title' => $title]);

            // Pasamos el email del usuario logueado como Reply-To para que el cliente pueda responderle
            $currentUserEmail = Yii::$app->user->identity->email;

            $sent = $emailService->sendHtml(
                $emailTo,
                $subject,
                $htmlContent,
                $currentUserEmail
            );

            if ($sent) {
                return ['success' => true, 'message' => Yii::t('app', 'Email sent successfully.')];
            } else {
                return ['success' => false, 'message' => Yii::t('app', 'Could not send email. Check SMTP logs.')];
            }

        } catch (\Exception $e) {
            Yii::error("Error sharing email: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function buildEmailBody($title, $format, $agency, $qrSrc, $url)
    {
        $grayText = 'color: #6c757d; font-family: Helvetica, Arial, sans-serif;';
        $orangeText = 'color: #FF6600; font-family: Helvetica, Arial, sans-serif;';

        return '
            <div style="text-align: center;">
                <p style="' . $grayText . ' font-weight: bold; margin-bottom: 5px; font-size: 14px;">
                    ' . Yii::t('app', 'Hey! AdShowcase has shared a preview with you') . '
                </p>
                
                <div style="margin: 20px 0px auto; padding: 10px; background: #f8f9fa; display: inline-block; border-radius: 8px;">
                    <img src="' . $qrSrc . '" alt="QR Code" width="200" height="200" style="display: block;">
                </div>
                
                <p style="' . $grayText . ' font-size: 12px; margin-bottom: 5px; margin-top: 0px;">
                    ' . Yii::t('app', 'Scan the QR code or click the button below') . '
                </p>
                
                <p style="' . $grayText . ' font-size: 14px; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px;margin-top: 35px;">
                    ' . Html::encode($format) . '
                </p>
                
                <h2 style="' . $orangeText . ' margin: 10px 0; font-size: 24px;">
                    ' . Html::encode($title) . '
                </h2>
                
                <p style="' . $grayText . ' font-size: 14px; margin-bottom: 20px;">
                    ' . Html::encode($agency) . '
                </p>
                
                <a href="' . Html::encode($url) . '" style="margin-bottom: 15px;background-color: #2563eb; color: #ffffff; padding: 14px 35px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 14px; display: inline-block; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);">
                    ' . Yii::t('app', 'Click here to see the preview') . '
                </a>
            </div>
        ';
    }
}