<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\format;

use app\controllers\actions\back_office\BaseDatatableAction;
use app\services\back_office\format\BackOfficeFormatListService;
use Yii;

final class FormatIndexAction extends BaseDataTableAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/format/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeFormatListService)->findAll();

        $nameClassUrl = 'format';

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
                ['data' => 'name', 'title' => Yii::t('app', 'Name')],
                ['data' => 'url_slug', 'title' => Yii::t('app', 'URL Slug')],
                ['data' => 'format', 'title' => Yii::t('app', 'Format')],
                ['data' => 'family', 'title' => Yii::t('app', 'Family')],
                ['data' => 'experience', 'title' => Yii::t('app', 'Experience')],
                ['data' => 'subtype', 'title' => Yii::t('app', 'Subtype')],
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
            'title' => Yii::t('app', 'Formats'),
            'idDataTable' => $idDataTable,
            'dataTableConfig' => $dataTableConfig,
        ]);
    }
}