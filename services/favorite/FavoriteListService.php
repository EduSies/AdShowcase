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
     * * @param int|null $limitImages Número máximo de imágenes a devolver por lista. Si es null, devuelve todas.
     */
    public function getUserFavorites(?int $limitImages = null): array
    {
        $userId = Yii::$app->user->id;
        $result = [];

        // Obtenemos TODOS los hashes (sin límite) para saber si es favorito
        $defaultHashes = Favorite::find()
            ->alias('fav')
            ->select(['c.hash'])
            ->joinWith('creative c', false) // Join sin traer todos los datos, solo para conectar id con hash
            ->where(['fav.user_id' => $userId])
            ->column();

        // Obtenemos las IMÁGENES
        $queryDefaults = Favorite::find()
            ->alias('fav')
            ->joinWith('creative c')
            ->where(['fav.user_id' => $userId])
            ->orderBy(['fav.created_at' => SORT_ASC]); // Orden cronológico (antiguos primero)

        // Aplicar límite solo si no es null
        if ($limitImages !== null) {
            $queryDefaults->limit($limitImages);
        }

        $latestDefaults = $queryDefaults->all();

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
            'itemsHashes' => $defaultHashes
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
            $listImages = [];

            foreach ($list->items as $item) {
                if ($item->creative) {
                    // Siempre recopilamos todos los hashes para la lógica de "Is Favorite"
                    $hashes[] = $item->creative->hash;

                    // Lógica para obtener imágenes según el límite
                    $shouldAddImage = ($limitImages === null || count($listImages) < $limitImages);

                    if ($shouldAddImage && !empty($item->creative->url_thumbnail)) {
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