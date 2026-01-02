<?php

declare(strict_types=1);

namespace app\controllers\actions\favorite;

use app\helpers\CreativeHelper;
use app\services\catalog\CatalogListService;
use app\services\favorite\FavoriteListService;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

final class FavoriteIndexAction extends BaseFavoriteAction
{
    public ?string $can = 'favorite.manage';
    public ?string $layout = 'main';
    public ?string $view = '@app/views/site/catalog/index';
    protected $serviceClass = CatalogListService::class;
    public ?string $routeAjaxSearch = null;

    public function run()
    {
        $this->ensureCan($this->can);

        $this->serviceClass = new $this->serviceClass();
        $request = Yii::$app->request;

        $hashFavoriteDetail = $request->get('hash');

        $urlFavoritesList = Url::to(['/favorites']);
        $this->routeAjaxSearch = $hashFavoriteDetail ? Url::to(['/favorites/detail/'.$hashFavoriteDetail]) : $urlFavoritesList;

        // Recoger y normalizar datos
        $normalizeInput = function ($paramName) use ($request) {
            $value = $request->post($paramName, $request->get($paramName));
            if (empty($value)) return [];
            return is_string($value) ? explode(',', $value) : $value;
        };

        $filters = [
            'products' => $normalizeInput('products'),
            'formats' => $normalizeInput('formats'),
            'devices' => $normalizeInput('devices'),
            'countries' => $normalizeInput('countries'),
            'search' => $request->post('search', $request->get('search')),
            'offset' => (int)$request->post('offset', 0),
            'limit' => (int)$request->post('limit', 12),
            'listHash' => $hashFavoriteDetail,
            'onlyFavorites' => empty($hashFavoriteDetail),
        ];

        // Obtener datos del catálogo
        $data = $this->serviceClass->getCatalogData($filters);

        // Obtener favoritos del usuario
        $listsFavorites = (new FavoriteListService())->getUserFavorites();

        // Iteramos sobre cada creatividad y le añadimos los campos calculados (iconos, urls, flags, favoritos)
        $preparedCreatives = array_map(function ($creative) use ($listsFavorites) {
            return CreativeHelper::prepareCreativeDisplayData($creative, $listsFavorites);
        }, $data['queryData']);

        // Respuesta AJAX
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'creatives' => $this->controller->renderPartial('@app/views/site/catalog/_card-list', [
                    'creatives' => $preparedCreatives,
                    'listsFavorites' => $listsFavorites,
                    'routeButtonSearch' => $this->routeAjaxSearch,
                    'isFavorites' => true,
                    'isFavoritesDetail' => $hashFavoriteDetail ? true : false,
                ]),
                'totalCards' => $data['totalCards'],
                'count' => count($data['queryData']),
                'availableOptions' => $data['availableOptions'] ?? null
            ];
        }

        $filteredListHash = '';
        $filteredListName = Yii::t('app', 'Your favorites');

        if ($hashFavoriteDetail) {
            foreach ($listsFavorites as $list) {
                // Comparamos hashes. Nota: El hash de "Your favorites" es null
                if ($list['hash'] === $hashFavoriteDetail) {
                    $filteredListHash = $list['hash'];
                    $filteredListName = $list['name'];
                    break;
                }
            }
        }

        // Renderizado visita completa
        $initialCreativesHtml = $this->controller->renderPartial('@app/views/site/catalog/_card-list', [
            'creatives' => $preparedCreatives,
            'listsFavorites' => $listsFavorites,
            'routeButtonSearch' => $this->routeAjaxSearch,
            'isFavorites' => true,
            'isFavoritesDetail' => $hashFavoriteDetail ? true : false,
        ]);

        return $this->controller->render($this->view, [
            'isFavorites' => true,
            'isFavoritesDetail' => $hashFavoriteDetail ? true : false,
            'listsFavorites' => $listsFavorites,
            'filteredListName' => $filteredListName,
            'filteredListHash' => $filteredListHash,
            'filters' => $data['filters'],
            'creatives' => $initialCreativesHtml,
            'totalCards' => $data['totalCards'],
            'pageTitle' => $hashFavoriteDetail ? Yii::t('app', 'Favorite details') : Yii::t('app', 'Favorites'),
            'ajaxUrl' => Url::to([$this->routeAjaxSearch]),
            'ajaxUrlCreateList' => Url::to(['favorite/create-list']),
            'ajaxUrlToggleItem' => Url::to(['favorite/toggle-item']),
            'ajaxUrlGetDropdown' => Url::to(['favorite/get-dropdown']),
            'availableOptions' => $data['availableOptions'] ?? null,
            'ajaxUrlUpdateList' => Url::to(['favorite/update-list']),
            'ajaxUrlMoveList' => Url::to(['favorite/move-list']),
            'ajaxUrlDeleteList' => Url::to(['favorite/delete-list']),
            'urlFavoritesList' => $urlFavoritesList,
        ]);
    }
}