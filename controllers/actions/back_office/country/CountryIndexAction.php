<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\country;

use app\controllers\actions\back_office\BaseDatatableAction;
use app\services\back_office\country\BackOfficeCountryListService;
use Yii;

final class CountryIndexAction extends BaseDataTableAction
{
    public ?string $can  = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/country/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeCountryListService)->findAll();

        $nameClassUrl = 'country';

        $rows = $this->addStatusColumn(
            $rows,
            '/layouts/partials/datatables/back_office/_status',
            'status'
        );

        $rows = $this->addActionsColumn(
            $rows,
            '/layouts/partials/datatables/back_office/_actions',
            'hash',
            $nameClassUrl
        );

        $idDataTable = $nameClassUrl . 'Table';

        $dataTableConfig = $this->buildDataTableConfig(
            $idDataTable,
            $rows,
            [
                ['data' => 'hash', 'title' => Yii::t('app', 'Hash'), 'visible' => false],
                ['data' => 'id', 'title' => Yii::t('app', 'ID')],
                ['data' => 'iso', 'title' => Yii::t('app', 'ISO')],
                ['data' => 'iso3', 'title' => Yii::t('app', 'ISO3')],
                ['data' => 'name', 'title' => Yii::t('app', 'Name')],
                ['data' => 'url_slug', 'title' => Yii::t('app', 'URL Slug')],
                ['data' => 'continent_code', 'title' => Yii::t('app', 'Continent Code')],
                ['data' => 'currency_code', 'title' => Yii::t('app', 'Currency Code')],
                [
                    'data' => 'status',
                    'title' => Yii::t('app', 'Status'),
                    'className' => 'dt-head-center dt-body-center text-center',
                    'width' => '100px',
                ],
                ['data' => 'created_at', 'title' => Yii::t('app', 'Created At')],
                ['data' => 'updated_at', 'title' => Yii::t('app', 'Updated At')],
                [
                    'data' => 'actions',
                    'title' => Yii::t('app', 'Actions'),
                    'orderable' => false,
                    'searchable' => false,
                    'className' => 'dt-head-center dt-body-center text-center',
                ],
            ]
        );

        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Countries'),
            'idDataTable' => $idDataTable,
            'dataTableConfig' => $dataTableConfig,
        ]);
    }
}