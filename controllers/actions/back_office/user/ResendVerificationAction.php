<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\user;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\services\auth\AuthService;
use Yii;

final class ResendVerificationAction extends BaseBackOfficeAction
{
    public ?string $can = 'users.manage';

    public function run(string $hash)
    {
        $this->ensureCan($this->can);

        $service = new AuthService();

        if ($service->resendUserVerification($hash)) {
            return $this->controller->asJson([
                'success' => true,
                'message' => Yii::t('app', 'Verification email sent successfully. Status set to Pending.')
            ]);
        }

        return $this->controller->asJson([
            'success' => false,
            'message' => Yii::t('app', 'Error sending verification email.')
        ]);
    }
}