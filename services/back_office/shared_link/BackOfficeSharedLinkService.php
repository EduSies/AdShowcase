<?php

namespace app\services\back_office\shared_link;

use app\models\Creative;
use app\models\SharedLink;
use app\models\SharedLinkAccessLog;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

final class BackOfficeSharedLinkService
{
    /**
     * Genera un nuevo enlace compartido.
     * @param string $creativeHash El hash de la creatividad
     * @param int $ttlHours Horas de vida (-1 para infinito)
     * @param int|null $maxUses Máximo de usos (opcional)
     * @param int $userId ID del usuario que crea el link
     */
    public function generateLink(string $creativeHash, int $ttlHours, ?int $maxUses, int $userId): SharedLink
    {
        // Buscar creatividad por Hash
        $creative = Creative::findOne(['hash' => $creativeHash]);

        if (!$creative) {
            throw new NotFoundHttpException('Creative not found');
        }

        // Calcular expiración
        $expiresAt = null;
        if ($ttlHours > 0) {
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$ttlHours} hours"));
        }

        // Crear Token Seguro
        $token = $this->generateUniqueToken();
        $hash = $this->generateUniqueHash();

        // Guardar en DB
        $link = new SharedLink();
        $link->hash = $hash;
        $link->token = $token;
        $link->creative_id = $creative->id;
        $link->user_id = $userId;
        $link->expires_at = $expiresAt;
        $link->max_uses = $maxUses;

        if (!$link->save()) {
            throw new \Exception('Error creating shared link: ' . json_encode($link->errors));
        }

        return $link;
    }

    /**
    * Busca el link y verifica si es válido
    */
    public function getAndValidateLink(string $token): SharedLink
    {
        $link = SharedLink::find()
            ->where(['token' => $token])
            ->with([
                'creative',
                'creative.format',
                'creative.agency',
                'creative.country',
                'creative.device'
            ])
            ->one();

        if (!$link) {
            throw new NotFoundHttpException(Yii::t('app', 'Link not found or invalid.'));
        }

        // Validamos que no esté expirado, revocado o agotado
        $this->ensureLinkIsValid($link);

        return $link;
    }

    /**
     * Esto solo se llama cuando estamos seguros de que vamos a mostrar la página.
     */
    public function registerAccess(SharedLink $link): void
    {
        // Registrar acceso (Log)
        $this->logAccess($link);

        // Incrementar contador
        $link->updateCounters(['used_count' => 1]);
    }

    /**
     * Verificar las reglas para permitir el acceso.
     */
    private function ensureLinkIsValid(SharedLink $link): void
    {
        // Revocado
        if ($link->revoked_at !== null) {
            throw new ForbiddenHttpException(Yii::t('app', 'This link has been revoked.'));
        }

        // Expirado por fecha
        if ($link->expires_at !== null && strtotime($link->expires_at) < time()) {
            throw new ForbiddenHttpException(Yii::t('app', 'This link has expired.'));
        }

        // Expirado por usos
        if ($link->max_uses !== null && $link->used_count >= $link->max_uses) {
            throw new ForbiddenHttpException(Yii::t('app', 'This link has reached its maximum usage limit.'));
        }
    }

    private function logAccess(SharedLink $link): void
    {
        $log = new SharedLinkAccessLog();
        $log->shared_link_id = $link->id;
        $log->ip = Yii::$app->request->userIP;
        $log->user_agent = Yii::$app->request->userAgent;
        $log->save();
    }

    private function generateUniqueToken(): string
    {
        do {
            $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        } while (SharedLink::find()->where(['token' => $token])->exists());

        return $token;
    }

    private function generateUniqueHash(): string
    {
        do {
            // Genera string alfanumérico aleatorio de 16 caracteres
            $hash = Yii::$app->security->generateRandomString(16);
        } while (SharedLink::find()->where(['hash' => $hash])->exists());

        return $hash;
    }
}