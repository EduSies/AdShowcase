<?php

/** @var yii\web\View $this */
/** @var string $title */
/** @var array $rows */

use nullref\datatable\DataTable;
use nullref\datatable\LinkColumn;
use yii\helpers\Url;

$this->title = $title;

//$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= DataTable::widget([
    'tableOptions' => ['class' => 'table table-striped table-bordered', 'id' => 'brandsTable'],
    'columns' => [
        'id',
        'hash',
        'name',
        'status',
        'created_at',
        'updated_at',
/*        [
            'class' => LinkColumn::class,
            'header' => '',
            'label' => 'Editar',
            'url' => function ($row) {
                return Url::to(['back-office/brand-update', 'id' => $row['id']]);
            },
            'options' => ['class' => 'btn btn-sm btn-primary'],
        ],*/
    ],
    'data' => $rows,
]) ?>