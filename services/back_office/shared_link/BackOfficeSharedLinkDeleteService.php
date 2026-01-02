<?php

declare(strict_types=1);

namespace app\services\back_office\shared_link;

use app\models\SharedLink;

final class BackOfficeSharedLinkDeleteService
{
    public function delete(string $hash): bool
    {
        $sharedLink = SharedLink::findOne(['hash' => $hash]);

        if (!$sharedLink) {
            return false;
        }

        if ($sharedLink->delete() === false) {
            return false;
        }

        return true;
    }
}