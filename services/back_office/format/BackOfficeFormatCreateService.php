<?php

declare(strict_types=1);

namespace app\services\back_office\format;

use app\models\Format;
use app\models\forms\back_office\FormatForm;

final class BackOfficeFormatCreateService
{
    /** Create entity from form (SCENARIO_CREATE). Returns model or null on error. */
    public function create(FormatForm $form): ?Format
    {
        $format = new Format();

        $format->setAttributes([
            'name' => $form->name,
            'format' => $form->format,
            'family' => $form->family,
            'experience' => $form->experience,
            'subtype' => $form->subtype,
            'status' => $form->status,
            'url_slug' => $form->url_slug,
        ]);

        if (!$format->save()) {
            $form->addErrors($format->getErrors());
            return null;
        }

        return $format;
    }
}