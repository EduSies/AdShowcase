<?php

declare(strict_types=1);

namespace app\controllers\actions\shared_link;

use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;

abstract class BaseSharedLinkAction extends Action
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
     * Otorga acceso temporal al visitante.
     * @param string $creativeHash
     * @param int $seconds Duración del pase (por defecto 300s = 5 minutos)
     */
    protected function grantAnonymousAccess(string $creativeHash, int $seconds = 300): void
    {
        $sessionKey = 'allowed_creative_hash_' . $creativeHash;
        Yii::$app->session->set($sessionKey, time() + $seconds);
    }

    protected function beforeRun()
    {
        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }

        return parent::beforeRun();
    }
}