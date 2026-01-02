<?php

declare(strict_types=1);

namespace app\services\back_office\shared_link;

use app\models\forms\back_office\SharedLinkForm;
use app\models\SharedLink;

final class BackOfficeSharedLinkUpdateService
{
    public function update(string $hash, SharedLinkForm $form): bool
    {
        $sharedLink = SharedLink::findOne(['hash' => $hash]);

        if (!$sharedLink) {
            return false;
        }

        $sharedLink->setAttributes([
            'expires_at' => empty($form->expires_at) ? null : $form->expires_at,
            'max_uses' => $form->max_uses,
            'note' => $form->note,
        ]);

        if (!$sharedLink->save()) {
            $form->addErrors($sharedLink->getErrors());
            return false;
        }

        return true;
    }
}