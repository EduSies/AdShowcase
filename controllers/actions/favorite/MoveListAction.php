<?php

declare(strict_types=1);

namespace app\controllers\actions\favorite;

use app\models\FavList;
use app\models\FavListItem;
use app\models\Favorite; // <--- IMPORTANTE: Añadir modelo de Favoritos Generales
use app\services\favorite\FavoriteListService;
use Yii;
use yii\web\Response;

final class MoveListAction extends BaseFavoriteAction
{
    public ?string $layout = 'main-catalog';

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $userId = Yii::$app->user->id;

        $fromHash = $request->post('fromHash');
        $toHash = $request->post('toHash'); // Puede ser null/vacio (Default List)

        // Validamos solo el origen. El destino puede estar vacío (significa Default List)
        if (!$fromHash) {
            return ['success' => false, 'message' => Yii::t('app', 'Invalid parameters')];
        }

        // Obtener lista ORIGEN
        $fromList = FavList::findOne(['hash' => $fromHash, 'user_id' => $userId]);

        if (!$fromList) {
            return ['success' => false, 'message' => Yii::t('app', 'Source list not found')];
        }

        // Determinar DESTINO
        $isTargetDefault = empty($toHash);
        $toList = null;

        if (!$isTargetDefault) {
            // Si hay hash, buscamos la lista custom de destino
            $toList = FavList::findOne(['hash' => $toHash, 'user_id' => $userId]);
            if (!$toList) {
                return ['success' => false, 'message' => Yii::t('app', 'Destination list not found')];
            }
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Obtenemos items a mover
            $itemsToMove = FavListItem::find()
                ->select('creative_id')
                ->where(['list_id' => $fromList->id])
                ->column();

            if (!empty($itemsToMove)) {
                foreach ($itemsToMove as $creativeId) {

                    if ($isTargetDefault) {
                        // --- CASO A: Mover a "Your Favorites" (Tabla Favorite) ---
                        $exists = Favorite::find()
                            ->where(['user_id' => $userId, 'creative_id' => $creativeId])
                            ->exists();

                        if (!$exists) {
                            $fav = new Favorite();
                            $fav->user_id = $userId;
                            $fav->creative_id = $creativeId;
                            // La tabla Favorite no suele tener hash propio, solo user_id + creative_id
                            if (!$fav->save()) {
                                throw new \Exception('Error saving to defaults: ' . print_r($fav->getErrors(), true));
                            }
                        }

                    } else {
                        // --- CASO B: Mover a otra Lista Custom (Tabla FavListItem) ---
                        $exists = FavListItem::find()
                            ->where(['list_id' => $toList->id, 'creative_id' => $creativeId])
                            ->exists();

                        if (!$exists) {
                            $newItem = new FavListItem();
                            $newItem->list_id = $toList->id;
                            $newItem->creative_id = $creativeId;
                            $newItem->hash = Yii::$app->security->generateRandomString(16);

                            if (!$newItem->save()) {
                                throw new \Exception('Error saving item: ' . print_r($newItem->getErrors(), true));
                            }
                        }
                    }
                }
            }

            // Limpieza: Borrar items de origen y la lista origen
            FavListItem::deleteAll(['list_id' => $fromList->id]);

            if (!$fromList->delete()) {
                throw new \Exception('Error deleting source list');
            }

            $transaction->commit();

            // Renderizar respuesta
            $isFavoritesDetail = filter_var($request->post('isFavoritesDetail', false), FILTER_VALIDATE_BOOLEAN);
            $listsFavorites = (new FavoriteListService())->getUserFavorites();

            $html = $this->controller->renderPartial('listsFavoritesSection', [
                'listsFavorites' => $listsFavorites,
                'isFavoritesDetail' => $isFavoritesDetail
            ]);

            return [
                'success' => true,
                'message' => Yii::t('app', 'Items moved successfully'),
                'html' => $html
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), 'favorite_move');

            return [
                'success' => false,
                'message' => Yii::t('app', 'Error moving items') . (YII_DEBUG ? ': ' . $e->getMessage() : '')
            ];
        }
    }
}