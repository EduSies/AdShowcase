<?php

declare(strict_types=1);

namespace app\services\favorite;

use app\models\FavList;
use app\models\Favorite;
use Yii;

final class FavoriteListService
{
    /**
     * Obtiene la estructura de listas de favoritos del usuario y los hashes que contienen.
     */
    public function getUserFavorites(): array
    {
        $userId = Yii::$app->user->id;
        $result = [];

        // LISTA POR DEFECTO ("Your favorites")
        // Obtenemos los hashes de las creatividades en la tabla simple
        $defaultHashes = Favorite::find()
            ->alias('fav')
            ->select(['c.hash'])
            ->joinWith('creative c', false) // Join sin traer todos los datos, solo para conectar id con hash
            ->where(['fav.user_id' => $userId])
            ->column();

        // Obtenemos la IMAGEN de la última añadida
        // Hacemos una query ligera limitando a 1 resultado ordenado por fecha
        $latestDefaults = Favorite::find()
            ->alias('fav')
            ->joinWith('creative c')
            ->where(['fav.user_id' => $userId])
            ->orderBy(['fav.created_at' => SORT_ASC]) // Asumiendo que Favorite tiene created_at
            ->limit(2)
            ->all();

        $defaultImages = [];
        foreach ($latestDefaults as $fav) {
            if ($fav->creative && !empty($fav->creative->url_thumbnail)) {
                $defaultImages[] = $fav->creative->url_thumbnail;
            }
        }

        $result[] = [
            'hash' => null, // Hash vacío identifica la lista default en el frontend
            'name' => Yii::t('app', 'Your favorites'),
            'images' => $defaultImages,
            'itemsHashes' => $defaultHashes // Array de strings ['hash1', 'hash2']
        ];

        // LISTAS PERSONALIZADAS
        // Traemos listas con sus items
        $customLists = FavList::find()
            ->alias('fl')
            ->where(['fl.user_id' => $userId])
            // Ordenamos items por ID descendente (el último añadido primero)
            ->with(['items' => function($q) {
                $q->orderBy(['id' => SORT_ASC]);
            }, 'items.creative'])
            ->all();

        foreach ($customLists as $list) {
            $hashes = [];
            $listImages = []; // Array para guardar las 2 imágenes

            foreach ($list->items as $item) {
                if ($item->creative) {
                    // Recopilar hash
                    $hashes[] = $item->creative->hash;

                    // Lógica para obtener las 2 primeras imágenes
                    if (count($listImages) < 2 && !empty($item->creative->url_thumbnail)) {
                        $listImages[] = $item->creative->url_thumbnail;
                    }
                }
            }

            $result[] = [
                'hash' => $list->hash,
                'name' => $list->name,
                'images' => $listImages,
                'itemsHashes' => $hashes
            ];
        }

        return $result;
    }
}