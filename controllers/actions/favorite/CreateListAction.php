<?php

namespace app\controllers\actions\favorite;

use app\services\favorite\FavoriteCreateService;
use Yii;
use yii\base\Action;
use yii\web\Response;

class CreateListAction extends Action
{
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $name = $request->post('name');

        try {
            $list = (new FavoriteCreateService())->createList(Yii::$app->user->id, $name);

            return [
                'success' => true,
                'message' => Yii::t('app', 'List created successfully'),
                'listHash' => $list->hash,
                'name' => $list->name
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}