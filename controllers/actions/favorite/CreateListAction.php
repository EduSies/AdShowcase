<?php

declare(strict_types=1);

namespace app\controllers\actions\favorite;

use app\services\favorite\FavoriteCreateService;
use app\services\favorite\FavoriteListService;
use Yii;
use yii\web\Response;

final class CreateListAction extends BaseFavoriteAction
{
    public ?string $layout = 'main-catalog';

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $name = $request->post('name');

        try {
            $list = (new FavoriteCreateService())->createList(Yii::$app->user->id, $name);

            $listsFavorites = (new FavoriteListService())->getUserFavorites();

            $html = $this->controller->renderPartial('listsFavoritesSection', [
                'listsFavorites' => $listsFavorites,
                'isFavoritesDetail' => false
            ]);

            return [
                'success' => true,
                'message' => Yii::t('app', 'New list created successfully and new favorite added'),
                'listHash' => $list->hash,
                'name' => $list->name,
                'html' => $html,
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}