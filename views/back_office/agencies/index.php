<?php

/** @var yii\web\View $this */
/** @var string $title */
/** @var array $rows */

use app\assets\DataTablesAsset;
use nullref\datatable\DataTable;
use nullref\datatable\DataTableColumn;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = $title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back Office')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Taxonomies')];
$this->params['breadcrumbs'][] = $this->title;

DataTablesAsset::register($this);

$deleteConfirmJs = \yii\helpers\Json::htmlEncode(Yii::t('app', 'Are you sure you want to delete this item?'));
$idDataTable = "agenciesTable";

?>

<?= DataTable::widget([
    'id' => $idDataTable,
    'scrollY' => 'calc(100vh - 374px)',
    'scrollX' => true,
    'scrollCollapse' => true,
    'language' => [
        'lengthMenu' => '_MENU_',
        'info' => Yii::t('app', 'Showing _START_ - _END_ of _TOTAL_'),
        'infoEmpty' => Yii::t('app', 'Showing 0 - 0 of 0'),
        'infoFiltered' => ' (_MAX_)',
        'loadingRecords' => Yii::t('app', 'Loading...'),
        'processing' => Yii::t('app', 'Loading...'),
    ],
    'pagingType' => new JsExpression('window.matchMedia("(max-width: 1200px)").matches ? "full" : "full_numbers"'),
    'pageLength' => 25,
    'lengthMenu' => [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, Yii::t('app', 'All')]
    ],
    'tableOptions' => ['class' => 'table hover w-100 text-nowrap'],
    'columns' => [
        [
            'data' => 'id',
            'title' => Yii::t('app', 'ID'),
        ],
        'hash',
        [
            'data' => 'name',
            'title' => Yii::t('app', 'Name'),
            'width' => '200px',
        ],
        [
            'data' => 'url_name',
            'title' => Yii::t('app', 'URL Name'),
        ],
        [
            'data' => 'status',
            'title' => Yii::t('app', 'Status'),
            'className' => 'dt-head-center dt-body-center text-center',
            'width' => '200px',
        ],
        [
            'data' => 'created_at',
            'title' => Yii::t('app', 'Created'),
        ],
        [
            'data' => 'updated_at',
            'title' => Yii::t('app', 'Updated'),
        ],
        [
            'class' => DataTableColumn::class,
            'title' => Yii::t('app', 'Actions'),
            'orderable' => false,
            'searchable' => false,
            'className' => 'dt-head-center dt-body-center text-center',
            'render' => new JsExpression('function (data,type,row) {
                var editUrl = "'.Url::to(['back-office/agency-update']).'/" + row.id;
                var delUrl  = "'.Url::to(['back-office/agency-delete']).'/" + row.id;

                return \'<div class="d-flex gap-2 justify-content-center">\'
                     + \'<a class="btn btn-sm btn-primary" href="\' + editUrl + \'">'.Yii::t('app','Edit').'</a>\'
                     + \'<a class="btn btn-sm btn-outline-danger js-agency-delete" data-href="\' + delUrl + \'">'.Yii::t('app','Delete').'</a>\'
                     + \'</div>\';
            }'),
        ],
    ],
    'data' => $rows,
]) ?>

<?php

$js = <<<JS

    (function(){
      $(document).on('click','a.js-agency-delete', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        let button = $(this);
        let url = button.data("href");
        
        swalFire({
            title: $deleteConfirmJs,
            confirmButtonText: "Continue",
            cancelButtonText: "Cancel",
            customClass: {container: 'swal2-cancel-pr-container'}
        }).then((dialog) => {
            if (dialog.isConfirmed) {
                $.ajax({
                    method: 'post',
                    url: url,
                    data: {}
                }).done(function (response) {
                    if (response.success === true) {
                        swalSuccess(response.message);
                        
                        let dt = $('#$idDataTable').DataTable();
                        let settings = dt.settings()[0] || {};
                        let hasAjax = !!(settings.oInit && settings.oInit.ajax) || !!settings.ajax || !!settings.sAjaxSource;
                        
                        if (hasAjax) {
                            dt.ajax.reload(null, false);
                        } else {
                            var tr = button.closest('tr');
                            dt.row(tr).remove().draw(false);
                        }
                    } else {
                        swalDanger(response.message);
                    }
                });
            }
        })
      });
    })();

JS;

$this->registerJs($js);

?>