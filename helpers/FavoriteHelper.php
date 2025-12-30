<?php

namespace app\helpers;

use Yii;
use yii\helpers\Html;
use app\widgets\Icon;

class FavoriteHelper
{
    /**
     * Renderiza el botón de alternar (Toggle) para añadir/quitar de favoritos.
     *
     * @param string|null $listHash El hash de la lista (null para la lista por defecto si aplica)
     * @param bool $isAdded Si el ítem ya está en la lista
     * @param bool $isCustom Si es una lista personalizada (true) o la por defecto (false)
     * @return string HTML del botón
     */
    public static function renderToggleButton(?string $listHash, bool $isAdded, bool $isCustom): string
    {
        // Contenido del estado "Añadir"
        $contentAdd = Html::tag('span',
            '<span>' . Yii::t('app', 'Add') . '</span>' . Icon::widget(['icon' => 'bi-plus-lg']),
            ['class' => 'd-flex align-items-center gap-2 state-add ' . ($isAdded ? 'd-none' : '')]
        );

        // Contenido del estado "Añadido"
        $contentAdded = Html::tag('span',
            '<span>' . Yii::t('app', 'Added') . '</span>' . Icon::widget(['icon' => 'bi-check2']),
            ['class' => 'd-flex align-items-center gap-2 state-added ' . (!$isAdded ? 'd-none' : '')]
        );

        // Determinar clases CSS según estado y tipo de lista
        $btnClass = $isAdded
            ? 'btn-success'
            : ($isCustom ? 'btn-light color-main-2 border-color-2' : 'btn-primary');

        return Html::button($contentAdd . $contentAdded, [
            'class' => 'btn ' . $btnClass . ' btn-sm rounded-pill px-3 toggle-list-btn',
            'data-list-hash' => $listHash,
            'data-action' => $isAdded ? 'remove' : 'add',
            'type' => 'button'
        ]);
    }
}