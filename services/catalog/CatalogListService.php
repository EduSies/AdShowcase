<?php

declare(strict_types=1);

namespace app\services\catalog;

use app\models\FavList;
use app\models\FavListItem;
use app\models\Favorite;
use Yii;
use app\models\Creative;
use app\models\Product;
use app\models\Format;
use app\models\Country;
use app\models\Device;
use app\helpers\StatusHelper;
use yii\db\ActiveQuery;
use yii\helpers\Inflector;

final class CatalogListService
{
    /**
     * Obtiene datos del catálogo y filtros disponibles dinámicamente.
     */
    public function getCatalogData(array $params): array
    {
        // Query Principal (Para las cards)
        $query = $this->buildQuery($params);

        // Totales y Paginación
        $totalCards = $query->count();

        $query->orderBy(['c.created_at' => SORT_DESC]);

        if (empty($params['listHash'])) {
            $limit = (isset($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 12;
            $query->limit($limit);

            if (isset($params['offset']) && $params['offset'] > 0) {
                $query->offset($params['offset']);
            }
        }

        $queryData = $query->asArray()->all();

        // Filtros Estáticos (Nombres, Slugs, etc.)
        $filtersList = $this->getFiltersList();

        // Disponibilidad Dinámica (Qué opciones ocultar)
        $hasActiveFilters = !empty($params['products']) ||
            !empty($params['formats']) || !empty($params['devices']) ||
            !empty($params['countries']) || !empty($params['search']);

        $availableOptions = $hasActiveFilters ? $this->getAvailableOptions($params) : null;

        return [
            'filters' => $filtersList,
            'queryData' => $queryData,
            'totalCards' => $totalCards,
            'availableOptions' => $availableOptions
        ];
    }

    /**
     * Calcula qué IDs son válidos para cada filtro basándose en la selección actual.
     */
    private function getAvailableOptions(array $params): array
    {
        return [
            'products' => $this->fetchDistinctIds($params, 'products', 'c.product_id', 'p.url_slug'),
            'formats' => $this->fetchDistinctIds($params, 'formats', 'c.format_id', 'f.url_slug'),
            'devices' => $this->fetchDistinctIds($params, 'devices', 'c.device_id', 'd.name'),
            'countries' => $this->fetchDistinctIds($params, 'countries', 'co.iso', 'co.url_slug'),
        ];
    }

    /**
     * Helper para ejecutar la query distinct excluyendo el filtro propio.
     */
    private function fetchDistinctIds(array $params, string $filterToExclude, string $colId, string $colSlug): array
    {
        // Clonamos params y eliminamos el filtro que estamos calculando
        $tempParams = $params;
        unset($tempParams[$filterToExclude]);

        // Construimos query sin ese filtro
        $query = $this->buildQuery($tempParams);

        // Seleccionamos solo la columna necesaria
        return $query->select($colId)->distinct()->column();
    }

    /**
     * Construye la ActiveQuery base. Extraída para reutilización.
     */
    private function buildQuery(array $params): ActiveQuery
    {
        $query = Creative::find()->alias('c')->where(['c.status' => StatusHelper::STATUS_ACTIVE]);

        // Joins necesarios
        $query->joinWith(['brand b', 'agency a', 'format f', 'country co', 'product p', 'device d']);

        if (!empty($params['listHash'])) {
            // Hacemos JOIN con la tabla de items y la tabla de listas
            // fli = FavListItem (tabla intermedia), fl = FavList (cabecera lista)
            $query->innerJoin(FavListItem::tableName() . ' fli', 'fli.creative_id = c.id');
            $query->innerJoin(FavList::tableName() . ' fl', 'fl.id = fli.list_id');

            // Filtramos por el hash de la lista y el usuario propietario
            $query->andWhere(['fl.hash' => $params['listHash']]);
            $query->andWhere(['fl.user_id' => Yii::$app->user->id]);
        } else if (!empty($params['onlyFavorites'])) {
            // Si filtramos por Favoritos por Defecto ("Your Favorites")
            // Hacemos INNER JOIN con la tabla ADSHOWCASE_favorite.
            $query->innerJoin(Favorite::tableName() . ' fav', 'fav.creative_id = c.id');

            // Filtramos por el usuario actual
            $query->andWhere(['fav.user_id' => Yii::$app->user->id]);
        }

        // Products
        if (!empty($params['products'])) {
            $query->andWhere(['or', ['c.product_id' => $params['products']], ['p.url_slug' => $params['products']]]);
        }

        // Formats
        if (!empty($params['formats'])) {
            $query->andWhere(['or', ['c.format_id' => $params['formats']], ['f.url_slug' => $params['formats']]]);
        }

        // Devices
        if (!empty($params['devices'])) {
            $query->andWhere(['or', ['c.device_id' => $params['devices']], ['d.name' => $params['devices']]]);
        }

        // Countries
        if (!empty($params['countries'])) {
            $query->andWhere(['or', ['co.iso' => $params['countries']], ['co.url_slug' => $params['countries']]]);
        }

        // Search
        if (!empty($params['search'])) {
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

        return $query;
    }

    /**
     * Obtiene las opciones para los filtros (Industry, Format, etc.) cacheado.
     */
    protected function getFiltersList(): array
    {
        $cacheKey = 'catalog_filters_list_v2';

        return Yii::$app->cache->getOrSet($cacheKey, function () {
            return [
                'industry' => $this->buildFilterOptions(Product::class, 'id', 'name', 'url_slug'),
                'formats' => $this->buildFilterOptions(Format::class, 'id', 'name', 'url_slug'),
                'countries' => $this->buildFilterOptions(Country::class, 'iso', 'name', 'url_slug'),
                'devices' => $this->buildFilterOptions(Device::class, 'id', 'name', null),
            ];
        }, 3600);
    }

    /**
     * Construye la estructura necesaria para dropdownList con atributos data-slug.
     */
    private function buildFilterOptions(string $modelClass, string $keyField, string $valueField, ?string $slugField): array
    {
        $select = [$keyField, $valueField];
        if ($slugField) {
            $select[] = $slugField;
        }

        $data = $modelClass::find()
            ->select($select)
            ->orderBy([$valueField => SORT_ASC])
            ->asArray()
            ->all();

        $items = [];
        $optionsAttributes = [];

        foreach ($data as $row) {
            $id = $row[$keyField];
            $name = $row[$valueField];

            // Llenamos lista simple [id => Nombre]
            $items[$id] = $name;

            // Calculamos Slug: Si viene de BD úsalo, si no, genéralo del nombre
            if ($slugField && !empty($row[$slugField])) {
                $slug = $row[$slugField];
            } else {
                $slug = Inflector::slug($name);
            }

            // Estructura específica para la propiedad 'options' de dropDownList
            $optionsAttributes[$id] = ['data-slug' => $slug];
        }

        return [
            'items' => $items,
            'attributes' => ['options' => $optionsAttributes]
        ];
    }
}