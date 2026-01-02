<?php

namespace app\services\email;

use Yii;
use yii\base\Component;

class EmailSenderService extends Component
{
    /**
     * Envía un correo HTML utilizando el layout y la vista 'generic'.
     *
     * @param string $to Dirección de destino
     * @param string $subject Asunto del correo
     * @param string $htmlContent Contenido (puede incluir etiquetas HTML como <b>, <br>, etc.)
     * @param string|null $replyTo (Opcional)
     * @return bool True si se envió correctamente
     */
    public function sendHtml(string $to, string $subject, string $htmlContent, string $replyTo = null): bool
    {
        try {
            $mailer = Yii::$app->mailer->compose(
                ['html' => 'generic'],
                ['contentBody' => $htmlContent]
            );

            $mailer->setTo($to)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setSubject($subject);

            if ($replyTo) {
                $mailer->setReplyTo($replyTo);
            }

            $result = $mailer->send();

            if ($result) {
                Yii::info("Correo HTML enviado a: $to", __METHOD__);
                return true;
            } else {
                Yii::error("El servidor SMTP devolvió false para: $to", __METHOD__);
                return false;
            }

        } catch (\Exception $e) {
            Yii::error("Excepción al enviar correo HTML: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}