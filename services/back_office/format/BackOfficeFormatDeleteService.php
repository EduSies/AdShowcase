<?php

declare(strict_types=1);

namespace app\services\back_office\format;

use app\models\Format;

final class BackOfficeFormatDeleteService
{
    /** Hard delete by PK. Returns true on success. */
    public function delete(string $hash): bool
    {
        $format = Format::findOne(['hash' => $hash]);

        if (!$format) {
            return false;
        }

        if ($format->delete() === false) {
            return false;
        }

        return true;
    }
}