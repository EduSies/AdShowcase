<?php

declare(strict_types=1);

namespace app\services\back_office\shared_link;

use app\models\SharedLink;
use yii\db\Expression;

final class BackOfficeSharedLinkRevokeService
{
    /**
     * Revoca el acceso a un SharedLink estableciendo revoked_at a NOW().
     */
    public function revoke(string $hash): bool
    {
        $sharedLink = SharedLink::findOne(['hash' => $hash]);

        if (!$sharedLink) {
            return false;
        }

        $sharedLink->revoked_at = new Expression('NOW()');

        if ($sharedLink->save(false, ['revoked_at']) === false) {
            return false;
        }

        return true;
    }
}