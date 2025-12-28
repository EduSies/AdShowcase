<?php

namespace app\services\favorite;

use app\models\FavList;
use app\models\FavListItem;
use app\models\Favorite;
use app\models\Creative;
use Yii;
use yii\web\NotFoundHttpException;

class FavoriteUpdateService
{
    /**
     * Añade o quita un item.
     * Si listHash es NULL/VACÍO -> Usa tabla ADSHOWCASE_favorite (Lista default).
     * Si listHash TIENE VALOR -> Usa tabla ADSHOWCASE_fav_list / ADSHOWCASE_fav_list_item (Listas custom).
     */
    public function toggleItem(int $userId, string $creativeHash, ?string $listHash, string $action): array
    {
        // Buscar la creatividad
        $creative = Creative::findOne(['hash' => $creativeHash]);
        if (!$creative) {
            throw new NotFoundHttpException(Yii::t('app', 'Creative not found'));
        }

        // Lista por Defecto (Tabla ADSHOWCASE_favorite)
        if (empty($listHash)) {
            $item = Favorite::findOne(['user_id' => $userId, 'creative_id' => $creative->id]);
            $success = false;

            if ($action === 'add') {
                if (!$item) {
                    $fav = new Favorite();
                    $fav->user_id = $userId;
                    $fav->creative_id = $creative->id;
                    $success = $fav->save();
                } else {
                    $success = true; // Ya existía
                }
            } elseif ($action === 'remove') {
                if ($item) {
                    $success = (bool)$item->delete();
                } else {
                    $success = true; // Ya no existía
                }
            }

            // Devolvemos null en listHash porque la lista default no tiene hash en esta tabla
            return [
                'success' => $success,
                'listHash' => null
            ];
        }

        // Listas Personalizadas (Tablas fav_list / fav_list_item)
        $list = FavList::findOne(['hash' => $listHash, 'user_id' => $userId]);
        if (!$list) {
            throw new NotFoundHttpException(Yii::t('app', 'List not found'));
        }

        // Buscar si existe la relación en la tabla de items
        $item = FavListItem::findOne([
            'list_id' => $list->id,
            'creative_id' => $creative->id
        ]);

        $success = false;

        if ($action === 'add') {
            if (!$item) {
                $newItem = new FavListItem();
                $newItem->list_id = $list->id;
                $newItem->creative_id = $creative->id;
                $newItem->hash = Yii::$app->security->generateRandomString(16);
                $success = $newItem->save();
            } else {
                $success = true;
            }
        } elseif ($action === 'remove') {
            if ($item) {
                $success = (bool)$item->delete();
            } else {
                $success = true;
            }
        }

        return [
            'success' => $success,
            'listHash' => $list->hash
        ];
    }
}