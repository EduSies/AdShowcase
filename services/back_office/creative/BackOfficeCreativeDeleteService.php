<?php

declare(strict_types=1);

namespace app\services\back_office\creative;

use app\models\Creative;

final class BackOfficeCreativeDeleteService
{
    /**
     * Elimina un Creative.
     */
    public function delete(int $id): bool
    {
        $creative = Creative::findOne($id);

        if (!$creative) {
            return false;
        }

        if ($creative->delete() === false) {
            $creative->addErrors($creative->getErrors());
            return false;
        }

        return true;
    }
}