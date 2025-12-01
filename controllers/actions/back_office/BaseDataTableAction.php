<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office;

use yii\web\JsExpression;

abstract class BaseDatatableAction extends BaseBackofficeAction
{
    /**
     * Default common DataTable options for BackOffice listings.
     *
     * Individual actions/views can merge/override these as needed.
     */
    protected function getDefaultDataTableOptions(): array
    {
        return [
            'scrollY' => 'calc(100vh - 489px)',
            'scrollX' => true,
            'scrollCollapse' => true,
            'order' => [[1, 'desc']],
            'language' => [
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
     * Helper to build a complete DataTable configuration array
     * merging the common defaults with specific settings.
     *
     * @param string $id           DOM id for the table.
     * @param array  $data         Rows to feed into DataTables.
     * @param array  $columns      Column configuration array.
     * @param array  $extraOptions Extra options to override/extend defaults.
     *
     * @return array
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
     * Helper to attach a pre-rendered HTML column to each row for DataTables.
     *
     * @param array         $rows           Raw rows from a back office list service.
     * @param string        $column         Name of the column to inject into each row (e.g. 'actions').
     * @param string        $view           View name or path (as used by Controller::renderPartial) that renders the HTML.
     * @param callable|null $paramsBuilder  Callback that receives the row and must return an array of params for the view.
     *                                      If null, the whole row is passed as ['row' => $row].
     *
     * @return array
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
     * Convenience helper specialized for an "actions" column
     * that expects a "hash" key in each row.
     *
     * @param array  $rows    Raw rows from a back office list service.
     * @param string $view    View name or path that renders the actions HTML.
     * @param string $hashKey Array key that contains the hash in each row.
     *
     * @return array
     */
    protected function addActionsColumn(array $rows, string $view, string $hashKey = 'hash', ?string $nameClassUrl = null): array
    {
        return $this->addRenderedColumn(
            $rows,
            'actions',
            $view,
            static function (array $row) use ($hashKey, $nameClassUrl): array {
                return [
                    'nameClassUrl' => $nameClassUrl,
                    'hash' => $row[$hashKey] ?? null,
                ];
            }
        );
    }
}