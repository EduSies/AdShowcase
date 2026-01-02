<?php

declare(strict_types=1);

namespace app\controllers\actions\preview;

use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;

abstract class BasePreviewAction extends Action
{
    public ?string $can = null;
    public ?string $view = null;

    protected function ensureCan(?string $perm): void
    {
        if ($perm && !Yii::$app->user->can($perm)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
        }
    }

    /**
     * Verifica si el usuario tiene permiso para ver el Mockup.
     */
    protected function ensureCreativeAccess(string $creativeHash): void
    {
        if (!Yii::$app->user->isGuest) {
            return;
        }

        $sessionKey = 'allowed_creative_hash_' . $creativeHash;

        // Recuperamos el tiempo de expiración de la sesión
        $expirationTime = Yii::$app->session->get($sessionKey);

        // Si no hay dato o no es numérico -> Excepción
        if (!$expirationTime || !is_numeric($expirationTime)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
        }

        if (time() > $expirationTime) {
            // El permiso temporal ha caducado.
            // Borramos la sesión para limpiar.
            Yii::$app->session->remove($sessionKey);

            throw new ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
        }

        return;
    }

    protected function beforeRun()
    {
        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }

        return parent::beforeRun();
    }
}