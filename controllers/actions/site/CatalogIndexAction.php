<?php

declare(strict_types=1);

namespace app\controllers\actions\site;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use app\models\Creative;
use app\models\Product;
use app\models\Format;
use app\models\Country;
use app\models\Device;
use app\helpers\StatusHelper;

final class CatalogIndexAction extends BaseSiteAction
{
    public ?string $layout = 'main-catalog';
    public ?string $view = '@app/views/site/catalog/index';

    public function run()
    {
        $pageTitle = Yii::t('app', 'Creative Catalog');
        $ajaxUrl = Url::to(['site/catalog']);

        $request = Yii::$app->request;

        // 1. RECOGER DATOS (GET tiene prioridad si es acceso directo, POST si es filtro AJAX)
        // Helper para normalizar inputs (String 'a,b' -> Array ['a','b'])
        $normalizeInput = function ($paramName) use ($request) {
            $value = $request->post($paramName, $request->get($paramName));

            if (empty($value)) {
                return [];
            }

            // Si es un string (viene por URL), lo convertimos a array
            if (is_string($value)) {
                return explode(',', $value);
            }

            return $value;
        };

        $filters = [
            'products' => $normalizeInput('products'),
            'formats' => $normalizeInput('formats'),
            'devices' => $normalizeInput('devices'),
            'countries' => $normalizeInput('countries'),
            'search' => $request->post('search', $request->get('search')),
            'limit' => $request->post('limit', 0),
        ];

        // 2. OBTENER DATOS
        $data = $this->getData($filters);
        $countCurrentBatch = 0;

        // 3. RESPUESTA AJAX (JSON)
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $countCurrentBatch = count($data['queryData']);

            return [
                'creatives' => $this->controller->renderPartial('catalog/_card-list', [
                    'creatives' => $data['queryData']
                ]),
                'totalCards' => $data['totalCards'],
                'count' => $countCurrentBatch,
            ];
        }

        // 4. CARGA INICIAL (HTML)
        $initialCreativesHtml = $this->controller->renderPartial('catalog/_card-list', [
            'creatives' => $data['queryData'],
            'count' => $countCurrentBatch,
        ]);

        return $this->controller->render($this->view, [
            'filters' => $data['filters'],
            'creatives' => $initialCreativesHtml,
            'totalCards' => $data['totalCards'],
            'pageTitle' => $pageTitle,
            'ajaxUrl' => $ajaxUrl,
        ]);
    }

    /**
     * Lógica central de búsqueda y filtrado
     */
    protected function getData(array $params): array
    {
        $query = Creative::find()->alias('c')->where(['c.status' => StatusHelper::STATUS_ACTIVE]);

        $query->joinWith(['brand b', 'agency a', 'format f', 'country co', 'product p', 'device d']);

        // 1. PRODUCTS (Industry)
        if (!empty($params['products'])) {
            $query->andWhere([
                'or',
                ['c.product_id' => $params['products']],
                ['p.url_slug' => $params['products']]
            ]);
        }

        // 2. FORMATS
        if (!empty($params['formats'])) {
            $query->andWhere([
                'or',
                ['c.format_id' => $params['formats']],
                ['f.url_slug' => $params['formats']]
            ]);
        }

        // 3. DEVICES
        if (!empty($params['devices'])) {
            $query->andWhere([
                'or',
                ['c.device_id' => $params['devices']],
                ['d.name' => $params['devices']]
            ]);
        }

        // 4. COUNTRIES
        if (!empty($params['countries'])) {
            $query->andWhere([
                'or',
                ['co.iso' => $params['countries']],
                ['co.url_slug' => $params['countries']]
            ]);
        }

        // --- BÚSQUEDA GENERAL (TEXTO) ---
        if (!empty($params['search'])) {
            // Unimos todas las tablas necesarias si no se unieron arriba
            $query->joinWith(['salesType st']);

            $query->andWhere([
                'or',
                ['like', 'c.hash', $params['search']],
                ['like', 'c.title', $params['search']],
                ['like', 'co.name', $params['search']],
                ['like', 'f.name', $params['search']],
                ['like', 'p.name', $params['search']],
                ['like', 'st.name', $params['search']],
            ]);
        }

        // --- LISTADOS PARA SELECTS (CACHÉ 1H) ---
        $cacheKey = 'catalog_filters_list';

        $filtersList = Yii::$app->cache->getOrSet($cacheKey, function () {
            return [
                'industry' => Product::find()->select(['name', 'id'])->orderBy(['name' => SORT_ASC])->indexBy('id')->column(),
                'formats' => Format::find()->select(['name', 'id'])->orderBy(['name' => SORT_ASC])->indexBy('id')->column(),
                'countries' => Country::find()->select(['name', 'iso'])->orderBy(['name' => SORT_ASC])->indexBy('iso')->column(),
                'devices' => Device::find()->select(['name', 'id'])->orderBy(['id' => SORT_ASC])->indexBy('id')->column(),
            ];
        }, 3600);

        // --- RESULTADOS ---
        $totalCards = $query->count();

        $query->orderBy(['c.created_at' => SORT_DESC]);
        $query->limit(12);

        if (isset($params['limit'])) {
            $query->offset($params['limit']);
        }

        //$campaigns = $query->createCommand()->rawSql;
        //dd($campaigns);
        $queryData = $query->asArray()->all();

        return [
            'filters' => $filtersList,
            'queryData' => $queryData,
            'totalCards' => $totalCards
        ];
    }
}