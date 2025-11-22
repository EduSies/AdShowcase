<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office;

use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;

abstract class BaseBackOfficeAction extends Action
{
    /** Permiso RBAC requerido para esta acción (si procede). */
    public ?string $can = null;

    /** Ruta de vista para renderizar (index/create/update…). */
    public ?string $view = null;

    /** Clase del modelo AR que gestiona esta acción. */
    public ?string $modelClass = null;

    protected function ensureCan(?string $perm): void
    {
        if ($perm && !Yii::$app->user->can($perm)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
        }
    }
}