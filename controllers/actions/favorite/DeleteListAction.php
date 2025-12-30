<?php

declare(strict_types=1);

namespace app\controllers\actions\favorite;

use app\models\FavList;
use app\services\favorite\FavoriteListService;
use Yii;
use yii\web\Response;

final class DeleteListAction extends BaseFavoriteAction
{
    public ?string $layout = 'main-catalog';

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $userId = Yii::$app->user->id;

        $hash = $request->post('listHash');

        if (!$hash) {
            return ['success' => false, 'message' => Yii::t('app', 'Invalid parameters')];
        }

        $list = FavList::findOne(['hash' => $hash, 'user_id' => $userId]);

        if (!$list) {
            return ['success' => false, 'message' => Yii::t('app', 'List not found')];
        }

        if ($list->delete()) {

            $isFavoritesDetail = filter_var($request->post('isFavoritesDetail', false), FILTER_VALIDATE_BOOLEAN);
            $listsFavorites = (new FavoriteListService())->getUserFavorites();

            $html = $this->controller->renderPartial('listsFavoritesSection', [
                'listsFavorites' => $listsFavorites,
                'isFavoritesDetail' => $isFavoritesDetail
            ]);

            return [
                'success' => true,
                'message' => Yii::t('app', 'List deleted successfully'),
                'html' => $html
            ];
        }

        return ['success' => false, 'message' => Yii::t('app', 'Error deleting list')];
    }
}