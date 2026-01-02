<?php

/** @var yii\web\View $this */
/** @var string $title */
/** @var string $idDataTable */
/** @var array $dataTableConfig */

use app\assets\DataTablesAsset;
use nullref\datatable\DataTable;
use yii\helpers\Json;

$this->title = $title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back Office')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Taxonomies')];
$this->params['breadcrumbs'][] = $this->title;

DataTablesAsset::register($this);

$this->registerJsVar('idDataTable', $idDataTable);
$this->registerJsVar('deleteConfirmJs', Json::htmlEncode(Yii::t('app', 'Are you sure you want to delete this item?')));

?>

<div class="card bg-transparent">
    <div class="card-content shadow-lg">
        <div class="level mb-4">
            <div class="level-left">
                <h1 class="title is-4">
                    <?= Yii::t('app', '{title} list', ['title' => $this->title]) ?>
                </h1>
            </div>
        </div>

        <?= DataTable::widget($dataTableConfig) ?>
    </div>
</div>