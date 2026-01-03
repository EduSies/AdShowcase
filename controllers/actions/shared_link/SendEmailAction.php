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
            $htmlContent = Yii::$app->mailer->render('shared-link/preview', [
                'title' => $title,
                'format' => $format,
                'agency' => $agency,
                'qrSrc' => $qrSrc,
                'url' => $sharedUrl
            ]);

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
}