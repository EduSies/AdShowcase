<?php

declare(strict_types=1);

namespace app\services\favorite;

use app\models\FavList;
use Yii;

final class FavoriteCreateService
{
    /**
     * Crea una nueva lista
     * @throws \Exception
     */
    public function createList(int $userId, string $name): FavList
    {
        // Validar nombre vacío
        if (trim($name) === '') {
            throw new \Exception(Yii::t('app', 'The list name cannot be empty'));
        }

        // Comprobar si ya existe una lista con ese nombre para este usuario
        $exists = FavList::findOne([
            'user_id' => $userId,
            'name' => $name
        ]);

        if ($exists) {
            throw new \Exception(Yii::t('app', 'You already have a list with this name'));
        }

        // Crear la lista si no existe
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