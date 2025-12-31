<?php

declare(strict_types=1);

namespace app\controllers\actions\favorite;

use app\services\favorite\FavoriteListService;
use app\services\favorite\FavoriteUpdateService;
use Yii;
use yii\web\Response;

final class ToggleItemAction extends BaseFavoriteAction
{
    public ?string $can = 'favorite.manage';
    public ?string $layout = 'main';

    public function run()
    {
        $this->ensureCan($this->can);

        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $listHash = $request->post('listHash'); // Puede ser null para "Your favorites"
        $creativeHash = $request->post('creativeHash');
        $action = $request->post('action'); // 'add' o 'remove'

        try {
            // Ejecutar la lógica (Add/Remove)
            $result = (new FavoriteUpdateService)->toggleItem(
                Yii::$app->user->id,
                $creativeHash,
                $listHash,
                $action
            );

            // Obtener los datos actualizados para la vista
            $listsFavorites = (new FavoriteListService())->getUserFavorites();

            // Renderizar la vista parcial
            $html = $this->controller->renderPartial('@adshowcase.layouts/partials/favorites/_list-favorites-items', [
                'listsFavorites' => $listsFavorites,
                'creativeHash' => $creativeHash
            ]);

            // Determinar si la creatividad es favorita en ALGUNA lista (para la estrella principal)
            $isFavoriteGlobal = false;
            foreach ($listsFavorites as $list) {
                if (in_array($creativeHash, $list['itemsHashes'])) {
                    $isFavoriteGlobal = true;
                    break;
                }
            }

            return [
                'success' => $result['success'],
                'listHash' => $result['listHash'],
                'message' => ($action === 'add') ? Yii::t('app', 'Added to list') : Yii::t('app', 'Removed from list'),
                'html' => $html,
                'isFavorite' => $isFavoriteGlobal,
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}