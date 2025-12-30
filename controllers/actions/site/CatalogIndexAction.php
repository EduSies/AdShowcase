<?php

declare(strict_types=1);

namespace app\controllers\actions\site;

use app\services\catalog\CatalogListService;
use app\services\favorite\FavoriteListService;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

final class CatalogIndexAction extends BaseSiteAction
{
    public ?string $layout = 'main-catalog';
    public ?string $view = '@app/views/site/catalog/index';
    protected $serviceClass = CatalogListService::class;
    public ?string $routeAjaxSearch = '/catalog';

    public function run()
    {
        $this->serviceClass = new $this->serviceClass();
        $request = Yii::$app->request;

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
        ];

        // Obtener datos del catálogo
        $data = $this->serviceClass->getCatalogData($filters);

        // Obtener favoritos del usuario
        $listsFavorites = (new FavoriteListService())->getUserFavorites(1);

        // Iteramos sobre cada creatividad y le añadimos los campos calculados (iconos, urls, flags, favoritos)
        $preparedCreatives = array_map(function ($creative) use ($listsFavorites) {
            return $this->prepareCreativeDisplayData($creative, $listsFavorites);
        }, $data['queryData']);

        // Respuesta AJAX
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'creatives' => $this->controller->renderPartial('catalog/_card-list', [
                    'creatives' => $preparedCreatives,
                    'listsFavorites' => $listsFavorites,
                    'routeButtonSearch' => $this->routeAjaxSearch,
                    'isFavorites' => true,
                    'isFavoritesDetail' => false,
                ]),
                'totalCards' => $data['totalCards'],
                'count' => count($data['queryData']),
                'availableOptions' => $data['availableOptions'] ?? null
            ];
        }

        // Renderizado visita completa
        $initialCreativesHtml = $this->controller->renderPartial('catalog/_card-list', [
            'creatives' => $preparedCreatives,
            'listsFavorites' => $listsFavorites,
            'routeButtonSearch' => $this->routeAjaxSearch,
            'isFavorites' => false,
            'isFavoritesDetail' => false,
        ]);

        return $this->controller->render($this->view, [
            'isFavorites' => false,
            'isFavoritesDetail' => false,
            'listsFavorites' => [],
            'filteredListName' => '',
            'filteredListHash' => '',
            'filters' => $data['filters'],
            'creatives' => $initialCreativesHtml,
            'totalCards' => $data['totalCards'],
            'pageTitle' => Yii::t('app', 'Creative Catalog'),
            'ajaxUrl' => Url::to([$this->routeAjaxSearch]),
            'ajaxUrlCreateList' => Url::to(['favorite/create-list']),
            'ajaxUrlToggleItem' => Url::to(['favorite/toggle-item']),
            'ajaxUrlGetDropdown' => Url::to(['favorite/get-dropdown']),
            'availableOptions' => $data['availableOptions'] ?? null,
            'ajaxUrlUpdateList' => Url::to(['favorite/update-list']),
            'ajaxUrlMoveList' => Url::to(['favorite/move-list']),
            'ajaxUrlDeleteList' => Url::to(['favorite/delete-list']),
            'urlFavoritesList' => '',
        ]);
    }

    /**
     * Procesa una creatividad individual añadiendo lógica de vista.
     */
    private function prepareCreativeDisplayData(array $creative, array $listsFavorites): array
    {
        // URL Detalle
        $creative['viewDetailUrl'] = Url::to(['creative/view', 'hash' => $creative['hash']]);

        // Textos seguros
        $creative['viewFormatName'] = !empty($creative['format']) ? $creative['format']['name'] : Yii::t('app', 'Format');
        $creative['viewAgencyName'] = !empty($creative['agency']) ? $creative['agency']['name'] : Yii::t('app', 'Agency');
        $creative['viewCountryCode'] = !empty($creative['country']) ? strtolower($creative['country']['iso']) : '';

        // Icono Dispositivo
        $creative['viewDeviceIcon'] = match ((int)$creative['device_id']) {
            1 => 'bi-display', // Desktop
            2 => 'bi-phone', // Mobile
            3 => 'bi-tablet', // Tablet
            default => 'bi-display',
        };

        // Lógica de Favoritos
        $isFavorite = false;
        if (!empty($listsFavorites)) {
            foreach ($listsFavorites as $list) {
                if (isset($list['itemsHashes']) && in_array($creative['hash'], $list['itemsHashes'])) {
                    $isFavorite = true;
                    break;
                }
            }
        }

        $creative['viewIsFavorite'] = $isFavorite;
        $creative['viewFavIcon'] = $isFavorite ? 'bi-star-fill' : 'bi-star';

        return $creative;
    }
}