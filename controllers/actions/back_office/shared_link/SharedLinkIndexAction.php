<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\shared_link;

use app\controllers\actions\back_office\BaseDatatableAction;
use app\services\back_office\shared_link\BackOfficeSharedLinkListService;
use Yii;

final class SharedLinkIndexAction extends BaseDataTableAction
{
    public ?string $can = 'share.manage';
    public ?string $view = '@app/views/back_office/shared_link/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeSharedLinkListService())->findAll();

        $nameClassUrl = 'shared-link';

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
                ['data' => 'full_name', 'title' => Yii::t('app', 'Shared by')],
                [
                    'data' => 'previews_used',
                    'title' => Yii::t('app', 'Previews Used'),
                    'className' => 'dt-head-center dt-body-center text-center',
                ],
                ['data' => 'created_at', 'title' => Yii::t('app', 'Created At')],
                ['data' => 'expires_at', 'title' => Yii::t('app', 'Expires At')],
                ['data' => 'revoked_at', 'title' => Yii::t('app', 'Revoke At')],
                ['data' => 'token', 'title' => Yii::t('app', 'Token Url')],
                ['data' => 'creative_name', 'title' => Yii::t('app', 'Creative Name')],
                ['data' => 'creative_hash', 'title' => Yii::t('app', 'Creative hash')],
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
            'title' => Yii::t('app', 'Shared Links'),
            'idDataTable' => $idDataTable,
            'dataTableConfig' => $dataTableConfig,
        ]);
    }
}