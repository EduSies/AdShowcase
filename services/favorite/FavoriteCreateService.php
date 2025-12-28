<?php

declare(strict_types=1);

namespace app\services\favorite;

use app\models\FavList;
use Yii;

final class FavoriteCreateService
{
    /**
     * Crea una nueva lista o devuelve la existente si es la por defecto
     */
    public function createList(int $userId, string $name): FavList
    {
        $list = new FavList();
        $list->user_id = $userId;
        $list->name = $name;
        $list->hash = Yii::$app->security->generateRandomString(16);

        if (!$list->save()) {
            throw new \Exception(Yii::t('app', 'Error creating list') . ': ' . print_r($list->errors, true));
        }

        return $list;
    }
}