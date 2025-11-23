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

$idDataTable = "productsTable";

?>

<div class="card bg-transparent">
    <div class="card-content">
        <div class="level mb-4">
            <div class="level-left"><h1 class="title is-4"><?= $this->title ?></h1></div>
            <div class="level-right">
                <a class="btn btn-outline-primary"
                   href="<?= Url::to(['back-office/product-create']) ?>"><?= Yii::t('app', 'New Product') ?></a>
            </div>
        </div>

        <?= DataTable::widget([
            'id' => $idDataTable,
            'scrollY' => 'calc(100vh - 489px)',
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
            'data' => $rows,
            'columns' => [
                ['data' => 'id', 'title' => Yii::t('app', 'ID')],
                ['data' => 'name', 'title' => Yii::t('app', 'Name')],
                ['data' => 'url_slug', 'title' => Yii::t('app', 'URL Slug')],
                [
                    'data' => 'status',
                    'title' => Yii::t('app', 'Status'),
                    'className' => 'dt-head-center dt-body-center text-center',
                    'width' => '200px',
                ],
                [
                    'class' => DataTableColumn::class,
                    'title' => Yii::t('app', 'Actions'),
                    'orderable' => false,
                    'searchable' => false,
                    'className' => 'dt-head-center dt-body-center text-center',
                    'render' => new JsExpression('function (data,type,row) {
                        var editUrl = "' . Url::to(['back-office/product-update']) . '/" + row.id;
                        var delUrl  = "' . Url::to(['back-office/product-delete']) . '/" + row.id;

                        return \'<div class="d-flex gap-2 justify-content-center">\'
                             + \'<a class="btn btn-sm btn-secondary" href="\' + editUrl + \'">' . Yii::t('app', 'Edit') . '</a>\'
                             + \'<a class="btn btn-sm btn-outline-danger js-delete" data-href="\' + delUrl + \'">' . Yii::t('app', 'Delete') . '</a>\'
                             + \'</div>\';
                    }'),
                ],
            ],
        ]) ?>
    </div>
</div>

<?php

$deleteConfirmJs = \yii\helpers\Json::htmlEncode(Yii::t('app', 'Are you sure you want to delete this item?'));

$js = <<<JS

    (function(){
      $(document).on('click','a.js-delete', function(e){
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