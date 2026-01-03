<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\creative;

use app\controllers\actions\back_office\BaseDatatableAction;
use app\helpers\StatusHelper;
use app\services\back_office\creative\BackOfficeCreativeListService;
use Yii;

final class CreativeIndexAction extends BaseDataTableAction
{
    public ?string $can = 'creative.manage';
    public ?string $view = '@app/views/back_office/creative/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeCreativeListService())->findAll();

        $nameClassUrl = 'creative';

        $rows = $this->addStatusColumn(
            $rows,
            '/layouts/partials/datatables/back_office/_status',
            'status'
        );

        $rows = $this->addStatusColumn(
            $rows,
            '/layouts/partials/datatables/back_office/_status',
            'workflow_status',
            [
                StatusHelper::WORKFLOW_DRAFT => ['color' => 'dark', 'icon' => 'bi bi-pencil'],
                StatusHelper::WORKFLOW_REVIEWED => ['color' => 'warning', 'icon' => 'bi bi-eye'],
                StatusHelper::WORKFLOW_APPROVED => ['color' => 'success', 'icon' => 'bi bi-check2-all'],
            ],
            StatusHelper::workflowStatusFilter()
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
                [
                    'data' => 'title',
                    'title' => Yii::t('app', 'Title'),
                    'className' => 'fw-bold',
                ],
                ['data' => 'brand_name', 'title' => Yii::t('app', 'Brand')],
                ['data' => 'agency_name', 'title' => Yii::t('app', 'Agency')],
                ['data' => 'country_name', 'title' => Yii::t('app', 'Country')],
                [
                    'data' => 'workflow_status',
                    'title' => Yii::t('app', 'Workflow'),
                    'className' => 'dt-head-center dt-body-center text-center',
                    'width' => '100px',
                ],
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
                    'width' => '100px',
                ],
            ]
        );

        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Creatives'),
            'idDataTable' => $idDataTable,
            'dataTableConfig' => $dataTableConfig,
        ]);
    }
}