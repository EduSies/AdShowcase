<?php

declare(strict_types=1);

namespace app\services\back_office\format;

use app\models\Format;
use app\models\forms\back_office\FormatForm;

final class BackOfficeFormatUpdateService
{
    /** Update entity by PK. Returns true on success. */
    public function update(int $id, FormatForm $form): bool
    {
        $format = Format::findOne($id);

        if (!$format) {
            $form->addErrors($format->getErrors());
            return false;
        }

        $format->setAttributes([
            'name' => $form->name,
            'format' => $form->format,
            'family' => $form->family,
            'experience' => $form->experience,
            'subtype' => $form->subtype,
            'status' => $form->status,
            'url_slug' => $form->url_slug,
        ]);

        if (!empty($form->hash)) {
            $format->hash = $form->hash;
        }

        if (!$format->save()) {
            $form->addErrors($format->getErrors());
            return false;
        }

        return true;
    }
}