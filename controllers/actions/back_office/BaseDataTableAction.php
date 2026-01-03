<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office;

use app\helpers\LangHelper;
use app\helpers\StatusHelper;
use yii\web\JsExpression;

abstract class BaseDatatableAction extends BaseBackofficeAction
{
    /**
     * Opciones comunes por defecto para DataTables en listados de BackOffice.
     *
     * Las acciones o vistas individuales pueden combinar (merge) o sobrescribir
     * estas opciones según sea necesario.
     * * @return array Configuración base del DataTable.
     */
    protected function getDefaultDataTableOptions(): array
    {
        return [
            'scrollY' => 'calc(100vh - 489px)',
            'scrollX' => true,
            'scrollCollapse' => true,
            'order' => [[1, 'desc']],
            'language' => [
                'search' => \Yii::t('app', 'Search'),
                'lengthMenu' => '_MENU_',
                'info' => \Yii::t('app', 'Showing _START_ - _END_ of _TOTAL_'),
                'infoEmpty' => \Yii::t('app', 'Showing 0 - 0 of 0'),
                'infoFiltered' => ' (_MAX_)',
                'loadingRecords' => \Yii::t('app', 'Loading...'),
                'processing' => \Yii::t('app', 'Loading...'),
            ],
            'pagingType' => new JsExpression(
                'window.matchMedia("(max-width: 1200px)").matches ? "full" : "full_numbers"'
            ),
            'pageLength' => 25,
            'lengthMenu' => [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, \Yii::t('app', 'All')],
            ],
            'tableOptions' => ['class' => 'table hover w-100 text-nowrap'],
        ];
    }

    /**
     * Ayudante para construir la configuración completa de DataTable, fusionando
     * los valores por defecto con las configuraciones específicas.
     *
     * @param string $id           ID del DOM para la tabla.
     * @param array  $data         Filas de datos para alimentar el DataTable.
     * @param array  $columns      Array de configuración de columnas.
     * @param array  $extraOptions Opciones extra para sobrescribir o extender los valores por defecto.
     *
     * @return array Configuración final fusionada.
     */
    protected function buildDataTableConfig(string $id, array $data, array $columns, array $extraOptions = []): array
    {
        return array_merge(
            $this->getDefaultDataTableOptions(),
            [
                'id' => $id,
                'data' => $data,
                'columns' => $columns,
            ],
            $extraOptions
        );
    }

    /**
     * Ayudante para adjuntar una columna HTML pre-renderizada a cada fila para DataTables.
     *
     * @param array         $rows           Filas crudas obtenidas de un servicio de lista de back office.
     * @param string        $column         Nombre de la columna a inyectar en cada fila (ej. 'actions').
     * @param string        $view           Nombre o ruta de la vista (usada por Controller::renderPartial) que renderiza el HTML.
     * @param callable|null $paramsBuilder  Callback que recibe la fila y debe retornar un array de parámetros para la vista.
     * Si es null, se pasa la fila completa como ['row' => $row].
     *
     * @return array Array de filas modificado.
     */
    protected function addRenderedColumn(array $rows, string $column, string $view, ?callable $paramsBuilder = null): array
    {
        $controller = $this->controller;

        return array_map(static function (array $row) use ($controller, $column, $view, $paramsBuilder): array {
            $params = $paramsBuilder ? $paramsBuilder($row) : ['row' => $row];

            $row[$column] = $controller->renderPartial($view, $params);

            return $row;
        }, $rows);
    }

    /**
     * Agrega una columna de Idioma usando la configuración centralizada de LangHelper.
     * Busca el idioma comparando el ID de la fila con el ID de la configuración.
     *
     * @param array  $rows      Datos de la tabla.
     * @param string $view      Vista parcial ('_language').
     * @param string $labelKey  La clave con el nombre del idioma (ej: 'language_name').
     * @param string $idKey     La clave con el ID del idioma en la fila (ej: 'language_id').
     */
    protected function addLanguageColumn(array $rows, string $view, string $labelKey = 'language_name', string $idKey = 'language_id'): array
    {
        // 1. Obtenemos la configuración maestra del Helper
        $langConfig = LangHelper::getLanguagesConfig();

        return $this->addRenderedColumn(
            $rows,
            $labelKey,
            $view,
            static function (array $row) use ($labelKey, $idKey, $langConfig): array {
                // Obtenemos el ID de idioma de la fila (ej: 2)
                $rowLangId = $row[$idKey] ?? null;

                $flag = 'xx';
                $label = $row[$labelKey] ?? 'Unknown';

                if ($rowLangId) {
                    // 2. Buscamos en la config qué idioma tiene este ID
                    foreach ($langConfig as $conf) {
                        // Comparamos el ID de la fila con el ID del Helper
                        if (isset($conf['id']) && (int)$conf['id'] === (int)$rowLangId) {
                            $flag = $conf['flag'];

                            // Si el nombre viene vacío en la fila, usamos el del Helper
                            if (empty($label) || $label === 'Unknown') {
                                $label = $conf['label'];
                            }
                            break; // Ya lo encontramos, salimos del bucle
                        }
                    }
                }

                return [
                    'flag'  => $flag,
                    'label' => $label,
                ];
            }
        );
    }

    /**
     * Agrega una columna renderizada para el Estado (Status).
     *
     * @param array  $rows            Datos de la tabla.
     * @param string $view            Vista parcial (ej: 'status').
     * @param string $statusKey       La clave con el valor del estado en la BD (ej: 'status').
     * @param array  $customVisualMap (Opcional) Mapa de 'VALOR' => ['color' => '...', 'icon' => '...']. Si se pasa, sustituye al por defecto.
     * @param array  $customLabels    (Opcional) Array de 'VALOR' => 'Etiqueta'. Si se pasa, sustituye a StatusHelper::statusFilter().
     */
    protected function addStatusColumn(array $rows, string $view, string $statusKey = 'status', array $customVisualMap = [], array $customLabels = []): array
    {
        $statusLabels = !empty($customLabels) ? $customLabels : StatusHelper::statusFilter();

        return $this->addRenderedColumn(
            $rows,
            $statusKey,
            $view,
            static function (array $row) use ($statusKey, $statusLabels, $customVisualMap): array {

                $status = $row[$statusKey] ?? null;

                // Definimos el mapa visual por defecto
                $defaultVisualMap = [
                    StatusHelper::STATUS_ACTIVE => ['color' => 'success', 'icon' => 'bi bi-check-circle-fill'],
                    StatusHelper::STATUS_PENDING => ['color' => 'warning', 'icon' => 'bi bi-hourglass-split'],
                    StatusHelper::STATUS_INACTIVE => ['color' => 'secondary', 'icon' => 'bi bi-slash-circle'],
                    StatusHelper::STATUS_BANNED => ['color' => 'danger', 'icon' => 'bi bi-slash-circle-fill'],
                    StatusHelper::STATUS_ARCHIVED => ['color' => 'dark', 'icon' => 'bi bi-archive-fill'],
                ];

                // Si nos pasaron un mapa custom, lo usamos. Si no, usamos el default.
                $mapToUse = !empty($customVisualMap) ? $customVisualMap : $defaultVisualMap;

                // Obtenemos la configuración o un fallback
                $config = $mapToUse[$status] ?? ['color' => 'secondary', 'icon' => 'bi bi-question-circle'];

                // Obtenemos la traducción
                $label = $statusLabels[$status] ?? $status;

                return [
                    'label' => $label,
                    'color' => $config['color'],
                    'icon' => $config['icon'],
                ];
            }
        );
    }

    /**
     * Agrega una columna renderizada específicamente para las Acciones (Botones).
     *
     * Prepara las variables necesarias ('nameClassUrl' y 'hash') para pasar
     * a la vista encargada de renderizar los botones de acción.
     *
     * @param array       $rows         El conjunto de datos original.
     * @param string      $view         Ruta a la vista parcial (ej: '_actions').
     * @param string      $hashKey      La clave en el array $row que contiene el hash único (ej: 'hash').
     * @param string|null $nameClassUrl El prefijo/nombre base para construir URLs y clases CSS (ej: 'user').
     *
     * @return array El array de filas modificado con la columna renderizada.
     */
    protected function addActionsColumn(array $rows, string $view, string $hashKey = 'hash', ?string $nameClassUrl = null): array
    {
        return $this->addRenderedColumn(
            $rows,
            'actions', // Nombre fijo para identificar la columna en el DataTable
            $view,
            static function (array $row) use ($hashKey, $nameClassUrl): array {
                return [
                    // Variable de configuración (estática para todas las filas)
                    'nameClassUrl' => $nameClassUrl,
                    // Variable de datos (dinámica por cada fila)
                    'hash' => $row[$hashKey] ?? null,
                ];
            }
        );
    }
}