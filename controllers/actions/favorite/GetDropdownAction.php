<?php

namespace app\controllers\actions\favorite;

use app\services\favorite\FavoriteListService;
use Yii;

final class GetDropdownAction extends BaseFavoriteAction
{
    public ?string $layout = 'main-catalog';

    public function run()
    {
        $creativeHash = Yii::$app->request->post('creativeHash');

        if (!$creativeHash) {
            return '';
        }

        // Obtenemos las listas actualizadas
        $listsFavorites = (new FavoriteListService())->getUserFavorites();

        // Renderizamos la vista del contenido del dropdown
        return $this->controller->renderPartial('@adshowcase.layouts/partials/favorites/_dropdown-favorites', [
            'creative' => ['hash' => $creativeHash], // Estructura mínima necesaria para la vista
            'listsFavorites' => $listsFavorites
        ]);
    }
}