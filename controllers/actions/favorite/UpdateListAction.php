<?php

declare(strict_types=1);

namespace app\controllers\actions\favorite;

use app\models\FavList;
use app\services\favorite\FavoriteListService;
use Yii;
use yii\web\Response;

final class UpdateListAction extends BaseFavoriteAction
{
    public ?string $layout = 'main-catalog';

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        // Validar parámetros
        $hash = $request->post('listHash');
        $newName = $request->post('name');

        if (!$hash || !$newName) {
            return ['success' => false, 'message' => Yii::t('app', 'Invalid parameters')];
        }

        // Buscar la lista y validar propiedad
        $list = FavList::findOne(['hash' => $hash, 'user_id' => Yii::$app->user->id]);

        if (!$list) {
            return ['success' => false, 'message' => Yii::t('app', 'List not found')];
        }

        $exists = FavList::find()
            ->where(['user_id' => Yii::$app->user->id, 'name' => $newName])
            ->andWhere(['!=', 'hash', $hash]) // Excluimos la lista que estamos editando
            ->exists();

        if ($exists) {
            return [
                'success' => false,
                'message' => Yii::t('app', 'You already have a list with this name')
            ];
        }

        // Actualizar
        $list->name = $newName;

        if ($list->save()) {
            // Obtener datos frescos para re-renderizar la sección
            $isFavoritesDetail = filter_var($request->post('isFavoritesDetail', false), FILTER_VALIDATE_BOOLEAN);

            $listsFavorites = (new FavoriteListService())->getUserFavorites();

            // Renderizar la vista parcial
            $html = $this->controller->renderPartial('listsFavoritesSection', [
                'listsFavorites' => $listsFavorites,
                'isFavoritesDetail' => $isFavoritesDetail
            ]);

            return [
                'success' => true,
                'message' => Yii::t('app', 'List renamed successfully'),
                'html' => $html
            ];
        }

        return [
            'success' => false,
            'message' => Yii::t('app', 'Error updating list name')
        ];
    }
}