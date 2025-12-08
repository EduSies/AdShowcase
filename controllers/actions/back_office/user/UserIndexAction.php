<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\user;

use app\controllers\actions\back_office\BaseDatatableAction;
use app\services\back_office\user\BackOfficeUserListService;
use Yii;

final class UserIndexAction extends BaseDataTableAction
{
    public ?string $can = 'users.manage';
    public ?string $view = '@app/views/back_office/user/index';

    public function run()
    {
        $this->ensureCan($this->can);

        $rows = (new BackOfficeUserListService())->findAll();

        $nameClassUrl = 'user';

        $rows = $this->addActionsColumn(
            $rows,
            '/layouts/partials/datatables/back_office/_actions',
            'hash',
            $nameClassUrl
        );

        $rows = $this->addLanguageColumn(
            $rows,
            '/layouts/partials/datatables/back_office/_language',
            'language_name',
            'language_id'
        );

        $idDataTable = $nameClassUrl . 'Table';

        $dataTableConfig = $this->buildDataTableConfig(
            $idDataTable,
            $rows,
            [
                ['data' => 'hash', 'title' => Yii::t('app', 'Hash'), 'visible' => false],
                ['data' => 'id', 'title' => Yii::t('app', 'ID')],
                ['data' => 'full_name', 'title' => Yii::t('app', 'Name')],
                ['data' => 'email_username', 'title' => Yii::t('app', 'Email & Username')],
                ['data' => 'type', 'title' => Yii::t('app', 'Type')],
                ['data' => 'language_name', 'title' => Yii::t('app', 'Default Language')],
                [
                    'data' => 'status',
                    'title' => Yii::t('app', 'Status'),
                    'className' => 'dt-head-center dt-body-center text-center',
                    'width' => '200px',
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
            'title' => Yii::t('app', 'Users'),
            'idDataTable' => $idDataTable,
            'dataTableConfig' => $dataTableConfig,
        ]);
    }
}